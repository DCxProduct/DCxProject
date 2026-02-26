<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserSessionTimeout
{
    private const TIMEOUT_MINUTES = 15;

    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $guard = Auth::guard('web');
        $hasRememberLogin = (bool) $request->session()->get('user_login_remember', false)
            || $request->cookie('user_remember_flag') === '1'
            || (method_exists($guard, 'viaRemember') && $guard->viaRemember());

        if ($hasRememberLogin) {
            $request->session()->put('user_login_remember', true);
            $request->session()->put('user_last_activity_at', time());
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
