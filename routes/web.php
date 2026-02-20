<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Models\Card;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $query = request('q');

    $cards = Card::query()
        ->when($query, function ($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%');
        })
        ->orderByRaw('shape_number IS NULL')
        ->orderBy('shape_number')
        ->latest('id')
        ->paginate(12)
        ->withQueryString();

    if (request()->ajax()) {
        return view('public.partials.cards', compact('cards'))->render();
    }

    return view('public.home', compact('cards', 'query'));
});

Route::get('/project/{slug}', function ($slug) {
    return view('public.project-detail', compact('slug'));
});

Route::get('/cards/{card}/open', function (Card $card) {
    if (!$card->link_url) {
        return redirect('/');
    }

    $user = auth()->user();
    $configuredAdminEmail = (string) config('app.admin_email');
    $isAdmin = $user
        && (
            (int) $user->id === 1
            || strtolower((string) $user->name) === 'admin'
            || ($configuredAdminEmail !== '' && strtolower((string) $user->email) === strtolower($configuredAdminEmail))
        );

    if ($card->require_login && $isAdmin) {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('user.login', [
            'next' => "/cards/{$card->id}/open",
        ]);
    }

    $hasRememberLogin = (bool) request()->session()->get('user_login_remember', false) || Auth::viaRemember();
    $currentPath = "/cards/{$card->id}/open";
    $allowPathOnce = (string) request()->session()->get('user_nonremember_allow_path', '');

    if ($card->require_login && !$hasRememberLogin && $allowPathOnce === $currentPath) {
        request()->session()->forget('user_nonremember_allow_path');
    } elseif ($card->require_login && auth()->check() && !$hasRememberLogin) {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('user.login', [
            'next' => $currentPath,
        ]);
    }

    if ($card->require_login && auth()->guest()) {
        return redirect()->route('user.login', [
            'next' => $currentPath,
        ]);
    }

    return redirect()->away($card->link_url);
})->middleware('user.timeout')->name('cards.open');

/*
|--------------------------------------------------------------------------
| Auth Routes (Laravel default)
|--------------------------------------------------------------------------
*/
Auth::routes(['register' => false]);
Route::get('/user/login', function () {
    $next = request()->query('next');

    if (Auth::guard('admin')->check()) {
        Auth::guard('admin')->logout();
    }

    if (Auth::check()) {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }

    return redirect()->route('login', array_filter([
        'next' => $next,
    ]));
})->name('user.login');

/*
|--------------------------------------------------------------------------
| Home (optional)
|--------------------------------------------------------------------------
*/
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});




