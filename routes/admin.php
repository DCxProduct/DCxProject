<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CardController;

Route::get('/', function () {
    return redirect('/');
})->name('admin.entry');

Route::get('/login', function () {
    return redirect()->route('login');
})->name('admin.login');

Route::post('/login', function () {
    return redirect()->route('login');
})->name('admin.login.submit');

Route::get('/dashboard', function () {
    return redirect('/');
})->name('admin.dashboard');

Route::middleware(['auth', 'admin', 'user.timeout'])->group(function () {
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    })->name('admin.logout');

    Route::get('/cards', function () {
        return redirect('/');
    })->name('admin.cards.index');

    Route::resource('cards', CardController::class)->except(['index'])->names('admin.cards');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});

