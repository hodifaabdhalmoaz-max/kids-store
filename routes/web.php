<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['verify' => true]);

// Language switching route
Route::get('/language/{locale}', [LanguageController::class, 'switchLang'])->name('language.switch');

// Public routes with basic rate limiting
Route::middleware(['security.headers', 'throttle:60,1'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home.index');
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/shop/search', [ShopController::class, 'search'])->name('shop.search');
    Route::get('/shop/quick-search', [ShopController::class, 'quickSearch'])->name('shop.quick.search');
    Route::get('/shop/category/{slug}', [ShopController::class, 'category'])->name('shop.category');
    Route::get('/shop/brand/{slug}', [ShopController::class, 'brand'])->name('shop.brand');
    Route::get('/shop/{product_slug}', [ShopController::class, 'product_details'])->name('shop.product.details');
    Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
    Route::post('/contact', [HomeController::class, 'contact_send'])->name('contact.send');
    Route::get('/about', [HomeController::class, 'about'])->name('about');
    Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
    Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
    Route::get('/returns', [HomeController::class, 'returns'])->name('returns');
    Route::get('/shipping', [HomeController::class, 'shipping'])->name('shipping');
    Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
    Route::post('/newsletter/subscribe', [HomeController::class, 'newsletter_subscribe'])->name('newsletter.subscribe');
});

//Cart - with stricter rate limiting for cart operations
Route::middleware(['security.headers', 'throttle:30,1'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add_to_cart'])->name('cart.add');
    Route::put('/cart/increase-quantity/{rowId}', [CartController::class, 'increase_cart_quantity'])->name('cart.qty.increase');
    Route::put('/cart/decrease-quantity/{rowId}', [CartController::class, 'decrease_cart_quantity'])->name('cart.qty.decrease');
    Route::delete('/cart/remove/{rowId}', [CartController::class, 'remove_item'])->name('cart.item.remove');
    Route::delete('/cart/clear', [CartController::class, 'empty_item'])->name('cart.empty');
    Route::post('/cart/apply-coupon', [CartController::class, 'apply_coupon_code'])->name('cart.coupon.apply');
    Route::delete('/cart/remove-coupon', [CartController::class, 'remove_coupon_code'])->name('cart.coupon.remove');
});

//checkout - very strict rate limiting for payment operations
Route::middleware(['auth', 'security.headers', 'rate.limit.strict:5,1'])->group(function () {
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/place-an-order', [CartController::class, 'place_an_order'])->name('cart.place.an.order');
    Route::get('/order-confirmation', [CartController::class, 'order_confirmation'])->name('cart.order.confirmation');
});

//wishlist - moderate rate limiting
Route::middleware(['security.headers', 'throttle:20,1'])->group(function () {
    Route::post('/wishlist/add', [WishlistController::class,'add_to_wishlist'])->name('wishlist.add');
    Route::get('/wishlist', [WishlistController::class,'index'])->name('wishlist.index');
    Route::delete('/wishlist/item/remove/{rowId}', [WishlistController::class,'remove_item'])->name('wishlist.item.remove');
    Route::delete('/wishlist/clear', [WishlistController::class,'empty_wishlist'])->name('wishlist.items.clear');
    Route::post('/wishlist/move-to-cart/{rowId}', [WishlistController::class,'move_to_cart'])->name('wishlist.move-to-cart');
});


// User dashboard - authenticated users only
Route::middleware(['auth', 'security.headers', 'throttle:30,1'])->group(function () {
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/user/orders', [UserController::class, 'orders'])->name('user.orders');
    Route::get('/user/wishlist', [UserController::class, 'wishlist'])->name('user.wishlist');
});

//Admin - very strict rate limiting for admin operations
Route::middleware(['auth', AuthAdmin::class, 'security.headers', 'rate.limit.strict:10,1'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    //Brand
    Route::get('/admin/brands', [AdminController::class, 'brands'])->name('admin.brands');
    Route::get('/admin/brand/add', [AdminController::class, 'add_brand'])->name('admin.brand.add');
    Route::post('/admin/brand/store', [AdminController::class, 'brand_store'])->name('admin.brand.store');
    Route::get('/admin/brand/edit/{id}', [AdminController::class, 'brand_edit'])->name('admin.brand.edit');
    Route::put('/admin/brand/update', [AdminController::class, 'brand_update'])->name('admin.brand.update');
    Route::delete('/admin/brand/{id}/delete', [AdminController::class, 'brand_delete'])->name('admin.brand.delete');

    //Category
    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/admin/category/add', [AdminController::class, 'category_add'])->name('admin.category.add');
    Route::post('/admin/category/store', [AdminController::class, 'category_store'])->name('admin.category.store');
    Route::get('/admin/category/edit/{id}', [AdminController::class, 'category_edit'])->name('admin.category.edit');
    Route::put('/admin/category/update', [AdminController::class, 'category_update'])->name('admin.category.update');
    Route::delete('/admin/category/{id}/delete', [AdminController::class, 'category_delete'])->name('admin.category.delete');

    //Product
    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/admin/product/add', [AdminController::class, 'product_add'])->name('admin.product.add');
    Route::post('/admin/product/store', [AdminController::class, 'product_store'])->name('admin.product.store');
    Route::get('/admin/product/edit/{id}', [AdminController::class, 'product_edit'])->name('admin.product.edit');
    Route::put('/admin/product/update', [AdminController::class, 'product_update'])->name('admin.product.update');
    Route::delete('/admin/product/{id}/delete', [AdminController::class, 'product_delete'])->name('admin.product.delete');

    //coupons
    Route::get('/admin/coupons', [AdminController::class, 'coupons'])->name('admin.coupons');
    Route::get('/admin/coupon/add', [AdminController::class, 'coupon_add'])->name('admin.coupon.add');
    Route::post('/admin/coupon/store', [AdminController::class, 'coupon_store'])->name('admin.coupon.store');
    Route::get('/admin/coupon/{id}edit', [AdminController::class, 'coupon_edit'])->name('admin.coupon.edit');
    Route::put('/admin/coupon/update', [AdminController::class, 'coupon_update'])->name('admin.coupon.update');
    Route::delete('/admin/coupon/{id}delete', [AdminController::class, 'coupon_delete'])->name('admin.coupon.delete');

    //Orders - إدارة الطلبات
    Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders');
    Route::get('/admin/order/{order}', [AdminOrderController::class, 'show'])->name('admin.order.details');
    Route::get('/admin/order/{order}/edit', [AdminOrderController::class, 'edit'])->name('admin.order.edit');
    Route::put('/admin/order/{order}', [AdminOrderController::class, 'update'])->name('admin.order.update');
    Route::put('/admin/order/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.order.update.status');
    Route::get('/admin/order/{order}/invoice', [AdminOrderController::class, 'invoice'])->name('admin.order.invoice');
    Route::get('/admin/order/{order}/tracking', [AdminOrderController::class, 'tracking'])->name('admin.order.tracking');
    Route::post('/admin/order/{order}/tracking', [AdminOrderController::class, 'addTracking'])->name('admin.order.tracking.add');
    Route::get('/admin/orders/statistics', [AdminOrderController::class, 'statistics'])->name('admin.orders.statistics');

    // Revenue Analytics - تحليل الإيرادات
    Route::get('/admin/revenue-analytics', [App\Http\Controllers\Admin\RevenueAnalyticsController::class, 'index'])->name('admin.revenue.analytics');
    Route::get('/admin/revenue-analytics/data', [App\Http\Controllers\Admin\RevenueAnalyticsController::class, 'getData'])->name('admin.revenue.data');
    Route::get('/admin/revenue-analytics/pdf', [App\Http\Controllers\Admin\RevenueAnalyticsController::class, 'downloadPDF'])->name('admin.revenue.pdf');
    Route::get('/admin/revenue-analytics/advanced', [App\Http\Controllers\Admin\RevenueAnalyticsController::class, 'advancedStats'])->name('admin.revenue.advanced');

    // اختبار سريع للنظام الجديد
    Route::get('/admin/test-revenue', function() {
        $service = new App\Services\RevenueAnalyticsService();
        $analytics = $service->getRevenueAnalytics('this_week');
        $summary = $service->getOverallSummary();

        return response()->json([
            'analytics' => $analytics,
            'summary' => $summary,
            'test_successful' => true
        ]);
    })->name('admin.test.revenue');
});
