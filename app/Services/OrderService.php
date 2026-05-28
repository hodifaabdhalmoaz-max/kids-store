<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Exception;

class OrderService
{
    protected $orderRepository;
    protected $cartService;

    public function __construct(OrderRepositoryInterface $orderRepository, CartService $cartService)
    {
        $this->orderRepository = $orderRepository;
        $this->cartService = $cartService;
    }

    /**
     * Create a new order
     *
     * @param array $orderData
     * @param string $paymentMode
     * @return Order
     * @throws Exception
     */
    public function createOrder(array $orderData, string $paymentMode): Order
    {
        // Validate cart is not empty
        if ($this->cartService->isEmpty()) {
            throw new Exception('السلة فارغة');
        }

        // Set checkout amounts
        $this->cartService->setCheckoutAmounts();
        $checkoutAmounts = $this->cartService->getCheckoutAmounts();

        if (!$checkoutAmounts) {
            throw new Exception('خطأ في حساب المبالغ');
        }

        return DB::transaction(function () use ($orderData, $paymentMode, $checkoutAmounts) {
            // Create or get address
            $address = $this->handleOrderAddress($orderData);

            // Create order
            $order = $this->createOrderRecord($address, $checkoutAmounts);

            // Create order items
            $this->createOrderItems($order);

            // Create transaction
            $this->createTransaction($order, $paymentMode);

            // Clear cart and sessions
            $this->cartService->clearCartAfterOrder();

            return $order;
        });
    }

    /**
     * Handle order address (create new or use existing)
     *
     * @param array $orderData
     * @return Address
     */
    protected function handleOrderAddress(array $orderData): Address
    {
        $userId = Auth::id();
        $address = Address::where('user_id', $userId)->where('isdefault', true)->first();

        if (!$address) {
            $address = new Address();
            $address->fill([
                'name' => $orderData['name'],
                'phone' => $orderData['phone'],
                'zip' => $orderData['zip'],
                'state' => $orderData['state'],
                'city' => $orderData['city'],
                'address' => $orderData['address'],
                'locality' => $orderData['locality'],
                'landmark' => $orderData['landmark'],
                'country' => 'Yemen',
                'user_id' => $userId,
                'isdefault' => true,
            ]);
            $address->save();
        }

        return $address;
    }

    /**
     * Create order record
     *
     * @param Address $address
     * @param array $checkoutAmounts
     * @return Order
     */
    protected function createOrderRecord(Address $address, array $checkoutAmounts): Order
    {
        $order = new Order();
        $order->fill([
            'user_id' => Auth::id(),
            'subtotal' => $checkoutAmounts['subtotal'],
            'discount' => $checkoutAmounts['discount'],
            'tax' => $checkoutAmounts['tax'],
            'total' => $checkoutAmounts['total'],
            'name' => $address->name,
            'phone' => $address->phone,
            'locality' => $address->locality,
            'address' => $address->address,
            'city' => $address->city,
            'state' => $address->state,
            'country' => $address->country,
            'landmark' => $address->landmark,
            'zip' => $address->zip,
            'type' => $address->type ?? 'home',
            'status' => 'ordered',
            'is_shipping_different' => false,
            'delivered_date' => null,
            'canceled_date' => null,
        ]);
        $order->save();

        return $order;
    }

    /**
     * Create order items from cart
     *
     * @param Order $order
     * @return void
     */
    protected function createOrderItems(Order $order): void
    {
        $cartItems = $this->cartService->getCartContents();

        foreach ($cartItems as $item) {
            $orderItem = new OrderItem();
            $orderItem->fill([
                'product_id' => $item->id,
                'order_id' => $order->id,
                'price' => $item->price,
                'quantity' => $item->qty,
                'options' => $item->options ?? [],
                'rstatus' => false,
            ]);
            $orderItem->save();
        }
    }

    /**
     * Create transaction record
     *
     * @param Order $order
     * @param string $paymentMode
     * @return Transaction
     */
    protected function createTransaction(Order $order, string $paymentMode): Transaction
    {
        $transaction = new Transaction();
        $transaction->fill([
            'user_id' => Auth::id(),
            'order_id' => $order->id,
            'mode' => $paymentMode,
            'status' => $this->getTransactionStatus($paymentMode),
        ]);
        $transaction->save();

        return $transaction;
    }

    /**
     * Get transaction status based on payment mode
     *
     * @param string $paymentMode
     * @return string
     */
    protected function getTransactionStatus(string $paymentMode): string
    {
        switch ($paymentMode) {
            case 'cod':
                return 'pending';
            case 'card':
            case 'paypal':
                return 'processing'; // Will be updated after payment gateway response
            case 'bank_transfer':
                return 'pending'; // Awaiting bank transfer confirmation
            case 'e_wallet':
                return 'pending'; // Awaiting e-wallet payment
            case 'installments':
                return 'pending'; // Awaiting installment setup
            default:
                return 'pending';
        }
    }

    /**
     * Update order status
     *
     * @param Order $order
     * @param string $status
     * @return bool
     */
    public function updateOrderStatus(Order $order, string $status): bool
    {
        return $this->orderRepository->updateStatus($order->id, $status);
    }

    /**
     * Cancel order
     *
     * @param Order $order
     * @param string|null $reason
     * @return bool
     */
    public function cancelOrder(Order $order, ?string $reason = null): bool
    {
        if (!$this->canCancelOrder($order)) {
            return false;
        }

        return $this->orderRepository->markAsCancelled($order->id, $reason);
    }

    /**
     * Check if order can be cancelled
     *
     * @param Order $order
     * @return bool
     */
    public function canCancelOrder(Order $order): bool
    {
        return in_array($order->status, ['ordered', 'processing']);
    }

    /**
     * Get order by ID with items
     *
     * @param int $orderId
     * @return Order|null
     */
    public function getOrderWithItems(int $orderId): ?Order
    {
        return $this->orderRepository->find($orderId);
    }

    /**
     * Get user orders with pagination
     *
     * @param User $user
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUserOrders(User $user, int $perPage = 10)
    {
        return $this->orderRepository->getByUser($user->id, $perPage);
    }

    /**
     * Get order statistics
     *
     * @return array
     */
    public function getOrderStatistics(): array
    {
        return $this->orderRepository->getStatistics();
    }

    /**
     * Get recent orders
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentOrders(int $limit = 10)
    {
        return $this->orderRepository->getRecent($limit);
    }

    /**
     * Process payment for order
     *
     * @param Order $order
     * @param string $paymentMode
     * @param array $paymentData
     * @return bool
     */
    public function processPayment(Order $order, string $paymentMode, array $paymentData = []): bool
    {
        switch ($paymentMode) {
            case 'card':
                return $this->processCardPayment($order, $paymentData);
            case 'paypal':
                return $this->processPaypalPayment($order, $paymentData);
            case 'cod':
                return $this->processCodPayment($order);
            default:
                return false;
        }
    }

    /**
     * Process card payment
     *
     * @param Order $order
     * @param array $paymentData
     * @return bool
     */
    protected function processCardPayment(Order $order, array $paymentData): bool
    {
        // TODO: Implement card payment processing
        // This would integrate with payment gateway like Stripe, PayPal, etc.
        return true;
    }

    /**
     * Process PayPal payment
     *
     * @param Order $order
     * @param array $paymentData
     * @return bool
     */
    protected function processPaypalPayment(Order $order, array $paymentData): bool
    {
        // TODO: Implement PayPal payment processing
        return true;
    }

    /**
     * Process cash on delivery
     *
     * @param Order $order
     * @return bool
     */
    protected function processCodPayment(Order $order): bool
    {
        // COD orders are automatically approved
        $transaction = $order->transaction;
        if ($transaction) {
            $transaction->status = 'pending';
            return $transaction->save();
        }
        return true;
    }

    /**
     * Store order ID in session for confirmation
     *
     * @param Order $order
     * @return void
     */
    public function storeOrderInSession(Order $order): void
    {
        Session::put('order_id', $order->id);
    }

    /**
     * Get order from session
     *
     * @return Order|null
     */
    public function getOrderFromSession(): ?Order
    {
        $orderId = Session::get('order_id');
        return $orderId ? $this->orderRepository->find($orderId) : null;
    }

    /**
     * Clear order from session
     *
     * @return void
     */
    public function clearOrderFromSession(): void
    {
        Session::forget('order_id');
    }
}
