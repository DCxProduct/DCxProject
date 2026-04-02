<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function status(Request $request)
    {
        $nextPath = (string) $request->query('next', '/');
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
    }

    public function loginRedirect(Request $request)
    {
        $next = $request->query('next');

        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->route('login', array_filter(['next' => $next]));
    }
}
