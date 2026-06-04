<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Product;
use App\Services\CartService;
use App\Services\OrderService;
use App\Http\Requests\PlaceOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $cartService;
    protected $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }
    public function index()
    {
        $items = $this->cartService->getCartContents();
        return view('cart', compact('items'));
    }

    public function add_to_cart(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $product = Product::findOrFail($request->id);
        $price = ($product->sale_price > 0 && $product->sale_price < $product->regular_price) 
            ? $product->sale_price 
            : $product->regular_price;

        $productData = [
            'id' => $product->id,
            'name' => $product->name,
            'quantity' => (int) $request->quantity,
            'price' => $price
        ];

        $this->cartService->addToCart($productData);
        return redirect()->back();
    }

    public function increase_cart_quantity($rowId)
    {
        $this->cartService->increaseQuantity($rowId);
        return redirect()->back();
    }

    public function decrease_cart_quantity($rowId)
    {
        $this->cartService->decreaseQuantity($rowId);
        return redirect()->back();
    }

    public function remove_item($rowId)
    {
        $this->cartService->removeItem($rowId);
        return redirect()->back();
    }

    public function empty_item()
    {
        $this->cartService->emptyCart();
        return redirect()->back();
    }
    public function apply_coupon_code(Request $request)
    {
        $result = $this->cartService->applyCoupon($request->coupon_code);

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }

    public function remove_coupon_code()
    {
        $result = $this->cartService->removeCoupon();
        return redirect()->back()->with('success', $result['message']);
    }
    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($this->cartService->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة');
        }

        $address = Address::where('user_id', Auth::user()->id)->where('isdefault', 1)->first();
        return view('checkout', compact('address'));
    }
    public function place_an_order(PlaceOrderRequest $request)
    {
        try {
            // الحصول على بيانات الطلب المتحقق منها
            $orderData = $request->getOrderData();
            $paymentMode = $orderData['mode'];

            // إزالة طريقة الدفع من بيانات العنوان
            unset($orderData['mode']);

            // إنشاء الطلب باستخدام OrderService
            $order = $this->orderService->createOrder($orderData, $paymentMode);

            // حفظ معرف الطلب في الجلسة
            $this->orderService->storeOrderInSession($order);

            return redirect()->route('cart.order.confirmation')->with('success', 'تم إنشاء طلبك بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء الطلب: ' . $e->getMessage());
        }
    }

    public function order_confirmation()
    {
        $order = $this->orderService->getOrderFromSession();

        if ($order) {
            return view('order-confirmation', compact('order'));
        }

        return redirect()->route('cart.index');
    }
}
