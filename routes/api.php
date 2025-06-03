<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::prefix('v1')->middleware('cache.response:300')->group(function () {
    // Authentication
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/featured', [ProductController::class, 'featured']);
    Route::get('/products/new-arrivals', [ProductController::class, 'newArrivals']);
    Route::get('/products/{slug}', [ProductController::class, 'show']);

    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{slug}', [CategoryController::class, 'show']);
    Route::get('/categories/{slug}/products', [ProductController::class, 'byCategory']);

    // Brands
    Route::get('/brands', [BrandController::class, 'index']);
    Route::get('/brands/{slug}', [BrandController::class, 'show']);
    Route::get('/brands/{slug}/products', [BrandController::class, 'products']);

    // Cart
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::put('/cart/{rowId}', [CartController::class, 'update']);
    Route::delete('/cart/{rowId}', [CartController::class, 'remove']);
    Route::delete('/cart', [CartController::class, 'clear']);
    Route::post('/cart/coupon', [CartController::class, 'applyCoupon']);
});

// Protected routes
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // User profile
    Route::get('/user', [UserController::class, 'profile']);
    Route::put('/user', [UserController::class, 'updateProfile']);
    Route::put('/user/password', [UserController::class, 'updatePassword']);

    // User addresses
    Route::get('/user/addresses', [UserController::class, 'addresses']);
    Route::post('/user/addresses', [UserController::class, 'storeAddress']);
    Route::put('/user/addresses/{id}', [UserController::class, 'updateAddress']);
    Route::delete('/user/addresses/{id}', [UserController::class, 'deleteAddress']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);

    // Wishlist
    Route::get('/wishlist', [UserController::class, 'wishlist']);
    Route::post('/wishlist/{productId}', [UserController::class, 'addToWishlist']);
    Route::delete('/wishlist/{id}', [UserController::class, 'removeFromWishlist']);

    // Reviews
    Route::post('/products/{productId}/reviews', [ProductController::class, 'storeReview']);
    Route::get('/user/reviews', [UserController::class, 'reviews']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Webhook routes
Route::prefix('v1/webhooks')->group(function () {
    Route::post('/payment-callback', [OrderController::class, 'paymentCallback']);
});
