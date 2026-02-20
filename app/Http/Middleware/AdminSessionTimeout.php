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
        if (!Auth::guard('admin')->check()) {
            return $next($request);
        }

        if (Auth::guard('admin')->viaRemember()) {
            $request->session()->put('admin_login_remember', true);
        }

        $hasRememberLogin = (bool) $request->session()->get('admin_login_remember', false);
        $currentPath = $request->getPathInfo();
        $allowPathOnce = (string) $request->session()->get('admin_nonremember_allow_path', '');

        if (!$hasRememberLogin) {
            if ($allowPathOnce !== '' && $allowPathOnce === $currentPath) {
                $request->session()->forget('admin_nonremember_allow_path');
            } else {
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('admin.login');
            }
        }

        $now = time();
        $lastActivity = (int) $request->session()->get('admin_last_activity_at', 0);
        $timeoutSeconds = self::TIMEOUT_MINUTES * 60;

        if ($lastActivity > 0 && ($now - $lastActivity) > $timeoutSeconds) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login');
        }

        $request->session()->put('admin_last_activity_at', $now);

        return $next($request);
    }
}
