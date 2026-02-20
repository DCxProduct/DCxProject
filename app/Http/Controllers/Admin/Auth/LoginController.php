<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admins.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login = trim($credentials['login']);
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (!Auth::guard('admin')->attempt([
            $field => $login,
            'password' => $credentials['password'],
        ], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'login' => ['These credentials do not match our records.'],
            ]);
        }

        $request->session()->regenerate();

        if (!$this->isAdmin(Auth::guard('admin')->user())) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'login' => ['This account is not allowed to access admin dashboard.'],
            ]);
        }

        $isRemember = $request->boolean('remember');
        $request->session()->put('admin_last_activity_at', time());
        $request->session()->put('admin_login_remember', $isRemember);

        $intended = (string) $request->session()->pull('url.intended', route('admin.dashboard'));
        $intendedPath = parse_url($intended, PHP_URL_PATH) ?: '/admin/dashboard';

        if (!$isRemember) {
            $request->session()->put('admin_nonremember_allow_path', $intendedPath);
        } else {
            $request->session()->forget('admin_nonremember_allow_path');
        }

        return redirect()->to($intendedPath);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function isAdmin($user): bool
    {
        $configuredAdminEmail = (string) config('app.admin_email');

        return $user
            && (
                (int) $user->id === 1
                || strtolower((string) $user->name) === 'admin'
                || ($configuredAdminEmail !== '' && strtolower((string) $user->email) === strtolower($configuredAdminEmail))
            );
    }
}
