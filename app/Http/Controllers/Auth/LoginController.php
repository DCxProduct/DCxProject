<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected function redirectTo(): string
    {
        return $this->isAdmin(auth()->user()) ? '/admin/dashboard' : '/';
    }

    /**
     * Use a single "login" input field (email or username).
     */
    public function username()
    {
        return 'login';
    }

    /**
     * Allow authentication with either email or name.
     */
    protected function credentials(Request $request)
    {
        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        return [
            $field => $login,
            'password' => $request->input('password'),
        ];
    }

    protected function authenticated(Request $request, $user)
    {
        // Reset admin timeout clock on each successful login.
        $request->session()->put('admin_last_activity_at', time());

        $next = trim((string) $request->input('next', ''));

        if ($next !== '' && $this->isSafeNextPath($next)) {
            return redirect()->to($next);
        }

        return null;
    }

    private function isSafeNextPath(string $next): bool
    {
        return str_starts_with($next, '/') && !str_starts_with($next, '//');
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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}

