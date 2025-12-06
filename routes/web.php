<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth Routes
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Google Auth
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');

Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
Route::get('/order/payment/{id}', [OrderController::class, 'payment'])->name('order.payment');
Route::get('/order/success/{id}', [OrderController::class, 'success'])->name('order.success');
Route::get('/orders', [OrderController::class, 'history'])->name('order.history');

// Voucher Check Route (Public or Auth depending on requirement, making it public for now as checkout is accessible)
Route::post('/vouchers/check', [VoucherController::class, 'check'])->name('vouchers.check');

// Admin/Owner/Cashier Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Cashier Routes
    Route::get('/cashier', [CashierController::class, 'index'])->name('cashier.dashboard');
    Route::post('/cashier/order/{id}/status', [CashierController::class, 'updateStatus'])->name('cashier.updateStatus');
    
    // Owner Routes
    Route::get('/owner', [OwnerController::class, 'index'])->name('owner.dashboard');

    // Menu & Voucher Management (Shared access for Owner & Cashier as requested)
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('vouchers', VoucherController::class);
});
