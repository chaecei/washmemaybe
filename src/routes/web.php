<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CategoryController;

// Redirect root to register page
Route::redirect('/', '/register');

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/category/{status}', [CategoryController::class, 'getByStatus']);
    
    // Notifications panel
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');

    // Account settings
    Route::get('/account/settings', [AccountController::class, 'showSettings'])->name('account.settings');
    Route::post('/account/update-info', [AccountController::class, 'updateInfo'])->name('account.updateInfo');
    Route::post('/account/change-password', [AccountController::class, 'changePassword'])->name('account.changePassword');

    // Services and Orders
    Route::get('/services', [ServiceController::class, 'showServices'])->name('services');
    Route::post('/orders/store', [ServiceController::class, 'storeOrder'])->name('storeOrder');
    Route::put('/orders/{order}/status', [ServiceController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/services/store', [ServiceController::class, 'store'])->name('service.store');
    Route::post('/payment/{order}', [ServiceController::class, 'storePayment'])->name('payment.store');
    Route::get('/service/{orderId}', [ServiceController::class, 'showServiceOrder']);

    // Route::resource('services', ServiceController::class);

    // Category Tables
    // Route::get('/dashboard/pending', [DashboardController::class, 'categories'])->name('dashboard.pending');

});
