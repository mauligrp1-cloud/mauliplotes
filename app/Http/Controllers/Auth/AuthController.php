<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show admin login form
     */
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->isStaffOrAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Process login request with rate limiting
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'max:72'],
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            \Illuminate\Support\Facades\Log::warning('Security: Login rate limit exceeded.', [
                'email' => $request->input('email'),
                'ip' => $request->ip()
            ]);
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $user = Auth::user();

            // Check if user has staff or admin role
            if (!$user->isStaffOrAdmin()) {
                \Illuminate\Support\Facades\Log::warning('Security: Unauthorized CMS login attempt by non-staff user.', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => $request->ip()
                ]);
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account does not have permission to access the administrative CMS.',
                ]);
            }

            $request->session()->regenerate();
            \Illuminate\Support\Facades\Log::info('Security: Successful CMS login.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip()
            ]);
            return redirect()->intended(route('admin.dashboard'));
        }

        RateLimiter::hit($throttleKey, 60);

        \Illuminate\Support\Facades\Log::warning('Security: Failed CMS login credentials.', [
            'email' => $request->input('email'),
            'ip' => $request->ip()
        ]);

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Process logout request
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            \Illuminate\Support\Facades\Log::info('Security: CMS user logged out.', [
                'user_id' => Auth::id(),
                'ip' => $request->ip()
            ]);
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('status', 'You have been logged out safely.');
    }
}
