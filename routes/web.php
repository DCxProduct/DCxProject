<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
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
})->middleware('user.timeout');

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

    $currentPath = "/cards/{$card->id}/open";

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

Route::middleware(['auth', 'user.timeout'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/auth/status', function () {
    $nextPath = (string) request()->query('next', '/');
    if (!str_starts_with($nextPath, '/') || str_starts_with($nextPath, '//')) {
        $nextPath = '/';
    }

    return response()->json([
        'authenticated' => Auth::check(),
        'controls_html' => view('public.partials.auth_controls', [
            'loggedUser' => Auth::user(),
            'nextPath' => $nextPath,
        ])->render(),
    ]);
})->name('auth.status');

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




