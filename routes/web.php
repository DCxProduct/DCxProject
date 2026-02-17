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
        ->latest()
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

/*
|--------------------------------------------------------------------------
| Auth Routes (Laravel default)
|--------------------------------------------------------------------------
*/
Auth::routes();
Route::redirect('/admin/login', '/login')->middleware('guest');

/*
|--------------------------------------------------------------------------
| Home (optional)
|--------------------------------------------------------------------------
*/
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
