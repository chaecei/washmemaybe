<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\RegisterController;

// Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [LoginController::class, 'login']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('/dashboard/pending', [DashboardController::class, 'showPending'])->name('dashboard.pending');
Route::get('/dashboard/unclaimed', [DashboardController::class, 'showProcessing'])->name('dashboard.unclaimed');
Route::post('/dashboard/process/{id}', [DashboardController::class, 'markAsProcessing'])->name('dashboard.process');
Route::get('/dashboard/processing', [DashboardController::class, 'showProcessing'])->name('dashboard.processing');

Route::post('/dashboard/readyforpickup/{id}', [DashboardController::class, 'markAsReady'])->name('dashboard.readyforpickup');
Route::get('/dashboard/ready', [DashboardController::class, 'showReady'])->name('dashboard.ready');

Route::post('/dashboard/unclaim/{id}', [DashboardController::class, 'markAsUnclaimed'])->name('markAsUnclaimed');

// Showing the Account Settings Page
Route::get('/account/settings', [AccountController::class, 'showSettings'])-> name('account.settings');
// Save the updated profile infor
Route::post('/account/update-info', [AccountController::class, 'updateInfo'])-> name('account.updateInfo');
// Changing the password
Route::post('/account/change-password', [AccountController::class, 'changePassword'])->name('account.changePassword');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Route::middleware('auth')->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    // other dashboard routes

// });

Route::get('/login', function () {
    return view('login'); // Make sure login.blade.php exists
})->name('login');

// Route::get('/', function () {
//     return view('welcome');
// });
