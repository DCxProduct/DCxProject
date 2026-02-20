<?php

use App\Http\Middleware\AdminSessionTimeout;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\UserSessionTimeout;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
       // web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        using: function () {


            Route::middleware('web')
                ->group(base_path('routes/web.php'));


            Route::middleware('web')
                ->namespace('App\Http\Controllers')
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));
        },
        health: '/up',



    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => EnsureAdmin::class,
            'admin.timeout' => AdminSessionTimeout::class,
            'user.timeout' => UserSessionTimeout::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();


