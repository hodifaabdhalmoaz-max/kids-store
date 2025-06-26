<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\AuthController;

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

// Public routes with rate limiting and security
Route::prefix('v1')->middleware(['security.headers', 'throttle:api', 'cache.response:300'])->group(function () {
    // Authentication - strict rate limiting for auth endpoints
    Route::middleware('rate.limit.strict:5,1')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
    });

    // Products
    Route::get('/products', [ApiProductController::class, 'index']);
    Route::get('/products/featured', [ApiProductController::class, 'featured']);
    Route::get('/products/latest', [ApiProductController::class, 'latest']);
    Route::get('/products/search', [ApiProductController::class, 'search']);
    Route::get('/products/{slug}', [ApiProductController::class, 'show']);

    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{slug}', [CategoryController::class, 'show']);
    Route::get('/categories/{slug}/products', [ApiProductController::class, 'byCategory']);

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

// Protected routes - Currently disabled, will be implemented when needed
// Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
//     // User profile, orders, wishlist, reviews, etc.
//     // Will be implemented in future versions
// });
