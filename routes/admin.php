<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CardController;
use App\Http\Controllers\Admin\DashboardController;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.legacy');
    Route::post('/logout', [UserController::class, 'logout'])->name('admin.logout');
    Route::resource('cards', CardController::class)->names('admin.cards');
});
