<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WasteCompanyController;
use App\Http\Controllers\ScrapDealerController;
use App\Http\Controllers\RecyclingPlantController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\CompanyProfileController;

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('index');


// Public routes (không cần đăng nhập)
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
// Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show'); // Moved to after resource routes
Route::get('/price-table', function() {
    $priceTables = \App\Models\PriceTable::with('wasteType')
        ->where('is_active', true)
        ->get()
        ->groupBy('wasteType.name');
    return view('price-table', compact('priceTables'));
})->name('price-table');

// Authentication Routes
Route::get('/logon', [AuthController::class, 'showLogon'])->name('logon');
Route::get('/login', [AuthController::class, 'showLogon'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showLogon'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

// Protected Routes
Route::middleware('auth')->group(function () {

    // Waste Company Routes
    Route::middleware('role:waste_company')->group(function () {
        Route::get('/waste-company/dashboard', [WasteCompanyController::class, 'dashboard'])->name('waste-company.dashboard');
        Route::get('/waste-company/posts', [WasteCompanyController::class, 'posts'])->name('waste-company.posts');
        Route::get('/waste-company/buy', [WasteCompanyController::class, 'buyWaste'])->name('waste-company.buy');
    });

    // Scrap Dealer Routes
    Route::middleware('role:scrap_dealer')->group(function () {
        Route::get('/scrap-dealer/dashboard', [ScrapDealerController::class, 'dashboard'])->name('scrap-dealer.dashboard');
        Route::get('/scrap-dealer/posts', [ScrapDealerController::class, 'posts'])->name('scrap-dealer.posts');
        Route::get('/scrap-dealer/buy', [ScrapDealerController::class, 'buyWaste'])->name('scrap-dealer.buy');
        Route::get('/scrap-dealer/inventory', [ScrapDealerController::class, 'inventory'])->name('scrap-dealer.inventory');
    });

    // Recycling Plant Routes
    Route::middleware('role:recycling_plant')->group(function () {
        Route::get('/recycling-plant/dashboard', [RecyclingPlantController::class, 'dashboard'])->name('recycling-plant.dashboard');
        Route::get('/recycling-plant/posts', [RecyclingPlantController::class, 'posts'])->name('recycling-plant.posts');
        Route::get('/recycling-plant/buy', [RecyclingPlantController::class, 'buyWaste'])->name('recycling-plant.buy');
    });

    // Post Management Routes (Common for all roles)
    Route::resource('posts', PostController::class)->except(['index', 'show']);
    Route::get('/posts/buy/{post}', [PostController::class, 'buy'])->name('posts.buy');
    Route::post('/posts/purchase/{post}', [PostController::class, 'purchase'])->name('posts.purchase');
    Route::get('/my-posts', [PostController::class, 'myPosts'])->name('posts.my-posts');

    // API for getting waste type price
    Route::get('/api/waste-type/{wasteTypeId}/price', [PostController::class, 'getWasteTypePrice'])->name('api.waste-type.price');

    // Review Routes
    Route::post('/posts/{post}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Collection Point Routes
    Route::resource('collection-points', \App\Http\Controllers\CollectionPointController::class);
    Route::get('/api/user-collection-points', [\App\Http\Controllers\CollectionPointController::class, 'getUserCollectionPoints'])->name('api.user-collection-points');

    // User Profile Routes
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');

    // Company Profile Routes
    Route::get('/company-profile', [CompanyProfileController::class, 'show'])->name('company-profile.show');
    Route::put('/company-profile', [CompanyProfileController::class, 'update'])->name('company-profile.update');

    // Cart Routes
    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::get('/cart/checkout', function() {
        return view('cart.checkout');
    })->name('cart.checkout');
    Route::get('/api/cart', [\App\Http\Controllers\CartController::class, 'getCart'])->name('cart.get');
    Route::post('/api/cart/add', [\App\Http\Controllers\CartController::class, 'addToCart'])->name('cart.add');
    Route::put('/api/cart/{id}/update', [\App\Http\Controllers\CartController::class, 'updateQuantity'])->name('cart.update');
    Route::delete('/api/cart/{id}', [\App\Http\Controllers\CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::delete('/api/cart', [\App\Http\Controllers\CartController::class, 'clearCart'])->name('cart.clear');

    // Order Routes
    Route::resource('orders', \App\Http\Controllers\OrderController::class);
});

// Public post detail route (placed after all specific routes to avoid conflicts)
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
