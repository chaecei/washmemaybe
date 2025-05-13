<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExpenseController;

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

    Route::get('/category/pending', [CategoryController::class, 'pending']);


    Route::get('/category/{status}', [CategoryController::class, 'getByStatus']);
    
    // Notifications panel
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::get('/notifications/fetch', [NotificationController::class, 'fetch'])->name('notifications.fetch');

    // Account settings
    Route::get('/account/settings', [AccountController::class, 'showSettings'])->name('account.settings');
    Route::post('/account/update-info', [AccountController::class, 'updateInfo'])->name('account.updateInfo');
    Route::post('/account/change-password', [AccountController::class, 'changePassword'])->name('account.changePassword');

    // Services and Orders
    Route::get('/services', [ServiceController::class, 'showServices'])->name('services');
    Route::post('/order/store', [ServiceController::class, 'storeOrder'])->name('order.store');
    Route::put('/orders/{order}/status', [ServiceController::class, 'updateStatus'])->name('orders.update-status');
    // Route::post('/services/store', [ServiceController::class, 'store'])->name('service.store');
    
    Route::get('/service/{orderId}', [ServiceController::class, 'showServiceOrder']);
    Route::get('/services/{id}', [ServiceController::class, 'show'])->name('services.show');

    
    Route::get('/payment/{order}', [ServiceController::class, 'showPayment'])->name('payment.show');
    Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');


    // Define the route for order history
    Route::get('history', [ServiceController::class, 'orderHistory'])->name('history');

    Route::get('expenses', [ServiceController::class, 'showExpenses'])->name('expenses');
    
    // Notification
    Route::get('/notifications/fetch', [NotificationController::class, 'fetch'])->name('notifications.fetch');

    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    // Route::resource('services', ServiceController::class);

    // Category Tables
    // Route::get('/dashboard/pending', [DashboardController::class, 'categories'])->name('dashboard.pending');

});
