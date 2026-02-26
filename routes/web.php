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
    $folderId = request('folder');
    $folderCard = null;
    $isFolderView = false;
    $user = auth()->user();
    $configuredAdminEmail = (string) config('app.admin_email');
    $isAdmin = $user
        && (
            (int) $user->id === 1
            || strtolower((string) $user->name) === 'admin'
            || ($configuredAdminEmail !== '' && strtolower((string) $user->email) === strtolower($configuredAdminEmail))
        );

    $cardsQuery = Card::query();
    $isAjaxRequest = request()->ajax();

    if ($folderId && !$isAjaxRequest) {
        return redirect('/');
    }

    if ($folderId && $isAjaxRequest) {
        $folderCard = Card::query()->find($folderId);

        if (!$folderCard || ($folderCard->destination_type ?? 'url') !== 'folder') {
            return response('<div class="alert alert-info mb-0">No Applications found!</div>', 200);
        }

        if ($folderCard->require_login && auth()->guest()) {
            return response('<div class="alert alert-warning mb-0">Please login first to open this Application!</div>', 200);
        }

        $cardsQuery->where('parent_id', $folderCard->id);
        $isFolderView = true;
    } else {
        $cardsQuery->whereNull('parent_id');
    }

    $perPage = $isFolderView ? 3 : 8;

    $cards = $cardsQuery
        ->when($query, function ($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%');
        })
        ->orderByRaw('shape_number IS NULL')
        ->orderBy('shape_number')
        ->latest('id')
        ->paginate($perPage)
        ->withQueryString();

    if (request()->ajax()) {
        return view('public.partials.cards', compact('cards', 'isAdmin', 'isFolderView'))->render();
    }

    return view('public.home', compact('cards', 'query', 'isAdmin', 'folderCard', 'isFolderView'));
})->middleware('user.timeout');

Route::get('/project/{slug}', function ($slug) {
    return view('public.project-detail', compact('slug'));
});

Route::get('/folders/{card}', function (Card $card) {
    return redirect()->route('cards.open', $card);
})->middleware('user.timeout')->name('folders.show');

Route::get('/cards/{card}/open', function (Card $card) {
    $currentPath = "/cards/{$card->id}/open";

    if ($card->require_login && auth()->guest()) {
        return redirect()->route('user.login', [
            'next' => $currentPath,
        ]);
    }

    if (($card->destination_type ?? 'url') === 'folder') {
        return redirect('/');
    }

    if (!$card->link_url) {
        return redirect('/');
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
})->middleware('user.timeout')->name('auth.status');

Route::get('/user/login', function () {
    $next = request()->query('next');

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




