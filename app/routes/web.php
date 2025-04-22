<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('/dashboard/pending', [DashboardController::class, 'showPending'])->name('dashboard.pending');
Route::get('/dashboard/processing', [DashboardController::class, 'showProcessing'])->name('dashboard.processing');
Route::get('/dashboard/ready', [DashboardController::class, 'showProcessing'])->name('dashboard.ready');
Route::get('/dashboard/unclaimed', [DashboardController::class, 'showProcessing'])->name('dashboard.unclaimed');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('/', function () {
    return view('welcome');
});
