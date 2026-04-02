<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ProfileController;
use App\Http\Controllers\Public\LandingController;
use App\Http\Controllers\Public\CardController;
use App\Http\Controllers\Public\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CardController as AdminCardController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->middleware('user.timeout')->name('public.home');

Route::get('/project/{slug}', [LandingController::class, 'projectDetail'])->name('public.project.detail');

Route::get('/folders/{card}', [CardController::class, 'show'])->middleware('user.timeout')->name('folders.show');

Route::get('/cards/{card}/open', [CardController::class, 'open'])->middleware('user.timeout')->name('cards.open');

/*
|--------------------------------------------------------------------------
| Auth Routes (Laravel default)
|--------------------------------------------------------------------------
*/
Auth::routes(['register' => false]);

Route::middleware(['auth', 'user.timeout'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/auth/status', [AuthController::class, 'status'])->middleware('user.timeout')->name('auth.status');

Route::get('/user/login', [AuthController::class, 'loginRedirect'])->name('user.login');

/*
|--------------------------------------------------------------------------
| Home (optional)
|--------------------------------------------------------------------------
*/
Route::get('/home', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::get('/admin', function () {
    return redirect('/');
})->name('admin.entry');

Route::get('/admin/login', function () {
    return redirect()->route('login');
})->name('admin.login');

Route::post('/admin/login', function () {
    return redirect()->route('login');
})->name('admin.login.submit');

Route::get('/admin/dashboard', function () {
    return redirect('/');
})->name('admin.dashboard');

Route::middleware(['auth', 'admin', 'user.timeout'])->prefix('admin')->group(function () {
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    })->name('admin.logout');

    Route::get('/cards', function () {
        return redirect('/');
    })->name('admin.cards.index');

    Route::resource('cards', AdminCardController::class)->except(['index'])->names('admin.cards');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});




