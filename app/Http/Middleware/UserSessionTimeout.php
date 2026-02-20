<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserSessionTimeout
{
    private const TIMEOUT_MINUTES = 30;

    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $configuredAdminEmail = (string) config('app.admin_email');
        $isAdmin = $user
            && (
                (int) $user->id === 1
                || strtolower((string) $user->name) === 'admin'
                || ($configuredAdminEmail !== '' && strtolower((string) $user->email) === strtolower($configuredAdminEmail))
            );

        if ($isAdmin) {
            return $next($request);
        }

        $now = time();
        $lastActivity = (int) $request->session()->get('user_last_activity_at', 0);
        $timeoutSeconds = self::TIMEOUT_MINUTES * 60;

        if ($lastActivity > 0 && ($now - $lastActivity) > $timeoutSeconds) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login', [
                'next' => $request->getPathInfo(),
            ]);
        }

        $request->session()->put('user_last_activity_at', $now);

        return $next($request);
    }
}
