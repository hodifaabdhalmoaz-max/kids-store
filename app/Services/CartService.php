<?php

namespace App\Services;

use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class CartService
{
    /**
     * Add product to cart
     *
     * @param array $productData
     * @return void
     */
    public function addToCart(array $productData): void
    {
        Cart::instance('cart')->add(
            $productData['id'],
            $productData['name'],
            $productData['quantity'],
            $productData['price']
        )->associate('App\Models\Product');
    }

    /**
     * Update cart item quantity
     *
     * @param string $rowId
     * @param int $quantity
     * @return void
     */
    public function updateCartQuantity(string $rowId, int $quantity): void
    {
        Cart::instance('cart')->update($rowId, $quantity);
    }

    /**
     * Increase cart item quantity by 1
     *
     * @param string $rowId
     * @return void
     */
    public function increaseQuantity(string $rowId): void
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        $this->updateCartQuantity($rowId, $qty);
    }

    /**
     * Decrease cart item quantity by 1
     *
     * @param string $rowId
     * @return void
     */
    public function decreaseQuantity(string $rowId): void
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = max(1, $product->qty - 1); // Ensure quantity doesn't go below 1
        $this->updateCartQuantity($rowId, $qty);
    }

    /**
     * Remove item from cart
     *
     * @param string $rowId
     * @return void
     */
    public function removeItem(string $rowId): void
    {
        Cart::instance('cart')->remove($rowId);
    }

    /**
     * Empty the entire cart
     *
     * @return void
     */
    public function emptyCart(): void
    {
        Cart::instance('cart')->destroy();
    }

    /**
     * Get cart contents
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCartContents()
    {
        return Cart::instance('cart')->content();
    }

    /**
     * Get cart count
     *
     * @return int
     */
    public function getCartCount(): int
    {
        return Cart::instance('cart')->count();
    }

    /**
     * Get cart subtotal
     *
     * @return string
     */
    public function getCartSubtotal(): string
    {
        return Cart::instance('cart')->subtotal();
    }

    /**
     * Get cart tax
     *
     * @return string
     */
    public function getCartTax(): string
    {
        return Cart::instance('cart')->tax();
    }

    /**
     * Get cart total
     *
     * @return string
     */
    public function getCartTotal(): string
    {
        return Cart::instance('cart')->total();
    }

    /**
     * Apply coupon code to cart
     *
     * @param string $couponCode
     * @return array
     */
    public function applyCoupon(string $couponCode): array
    {
        if (empty($couponCode)) {
            return [
                'success' => false,
                'message' => 'كود الخصم غير صحيح!'
            ];
        }

        $coupon = $this->validateCoupon($couponCode);
        
        if (!$coupon) {
            return [
                'success' => false,
                'message' => 'كود الخصم غير صحيح أو منتهي الصلاحية!'
            ];
        }

        // Store coupon in session
        Session::put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'cart_value' => $coupon->cart_value
        ]);

        // Calculate discount
        $this->calculateDiscount();

        return [
            'success' => true,
            'message' => 'تم تطبيق كود الخصم بنجاح!'
        ];
    }

    /**
     * Validate coupon code
     *
     * @param string $couponCode
     * @return Coupon|null
     */
    protected function validateCoupon(string $couponCode): ?Coupon
    {
        return Coupon::where('code', $couponCode)
            ->where('expiry_date', '>=', Carbon::today())
            ->where('cart_value', '<=', $this->getCartSubtotal())
            ->first();
    }

    /**
     * Calculate discount based on applied coupon
     *
     * @return void
     */
    public function calculateDiscount(): void
    {
        $discount = 0;
        
        if (Session::has('coupon')) {
            $coupon = Session::get('coupon');
            
            if ($coupon['type'] === 'fixed') {
                $discount = $coupon['value'];
            } else {
                $discount = ($this->getCartSubtotal() * $coupon['value']) / 100;
            }

            $subtotalAfterDiscount = $this->getCartSubtotal() - $discount;
            $taxAfterDiscount = ($subtotalAfterDiscount * config('cart.tax')) / 100;
            $totalAfterDiscount = $subtotalAfterDiscount + $taxAfterDiscount;

            Session::put('discounts', [
                'discount' => number_format(floatval($discount), 2, '.', ''),
                'subtotal' => number_format(floatval($subtotalAfterDiscount), 2, '.', ''),
                'tax' => number_format(floatval($taxAfterDiscount), 2, '.', ''),
                'total' => number_format(floatval($totalAfterDiscount), 2, '.', ''),
            ]);
        }
    }

    /**
     * Remove coupon from cart
     *
     * @return array
     */
    public function removeCoupon(): array
    {
        Session::forget(['coupon', 'discounts']);
        
        return [
            'success' => true,
            'message' => 'تم إزالة كود الخصم بنجاح!'
        ];
    }

    /**
     * Check if coupon is applied
     *
     * @return bool
     */
    public function hasCoupon(): bool
    {
        return Session::has('coupon');
    }

    /**
     * Get applied coupon details
     *
     * @return array|null
     */
    public function getAppliedCoupon(): ?array
    {
        return Session::get('coupon');
    }

    /**
     * Get discount details
     *
     * @return array|null
     */
    public function getDiscountDetails(): ?array
    {
        return Session::get('discounts');
    }

    /**
     * Set checkout amounts in session
     *
     * @return void
     */
    public function setCheckoutAmounts(): void
    {
        if (!$this->getCartCount() > 0) {
            Session::forget('checkout');
            return;
        }

        if ($this->hasCoupon()) {
            $discounts = $this->getDiscountDetails();
            Session::put('checkout', [
                'discount' => $discounts['discount'],
                'subtotal' => $discounts['subtotal'],
                'tax' => $discounts['tax'],
                'total' => $discounts['total'],
            ]);
        } else {
            Session::put('checkout', [
                'discount' => 0,
                'subtotal' => $this->getCartSubtotal(),
                'tax' => $this->getCartTax(),
                'total' => $this->getCartTotal(),
            ]);
        }
    }

    /**
     * Get checkout amounts
     *
     * @return array|null
     */
    public function getCheckoutAmounts(): ?array
    {
        return Session::get('checkout');
    }

    /**
     * Clear cart and related sessions after order
     *
     * @return void
     */
    public function clearCartAfterOrder(): void
    {
        $this->emptyCart();
        Session::forget(['checkout', 'coupon', 'discounts']);
    }

    /**
     * Check if cart is empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->getCartCount() === 0;
    }

    /**
     * Get cart summary for display
     *
     * @return array
     */
    public function getCartSummary(): array
    {
        $summary = [
            'items' => $this->getCartContents(),
            'count' => $this->getCartCount(),
            'subtotal' => $this->getCartSubtotal(),
            'tax' => $this->getCartTax(),
            'total' => $this->getCartTotal(),
            'has_coupon' => $this->hasCoupon(),
        ];

        if ($this->hasCoupon()) {
            $summary['coupon'] = $this->getAppliedCoupon();
            $summary['discounts'] = $this->getDiscountDetails();
        }

        return $summary;
    }
}
