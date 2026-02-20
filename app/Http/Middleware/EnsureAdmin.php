<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('admin')->user();
        $configuredAdminEmail = (string) config('app.admin_email');

        $isAdmin = $user
            && (
                $user->id === 1
                || strtolower((string) $user->name) === 'admin'
                || ($configuredAdminEmail !== '' && strtolower((string) $user->email) === strtolower($configuredAdminEmail))
            );

        if (!$isAdmin) {
            return redirect('/login');
        }

        return $next($request);
    }
}
