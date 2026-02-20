<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminSessionTimeout
{
    private const TIMEOUT_MINUTES = 60;

    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $now = time();
        $lastActivity = (int) $request->session()->get('admin_last_activity_at', 0);
        $timeoutSeconds = self::TIMEOUT_MINUTES * 60;

        if ($lastActivity > 0 && ($now - $lastActivity) > $timeoutSeconds) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login', [
                'next' => '/admin/dashboard',
            ]);
        }

        $request->session()->put('admin_last_activity_at', $now);

        return $next($request);
    }
}
