<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use LdapRecord\Container;

class LoginController extends Controller
{
    /**
     * Max failed attempts (per email + IP) before a timed lockout kicks in.
     */
    protected int $maxAttempts = 5;

    public function showLoginForm(): View
    {
        return view('adminlte.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $this->ensureIsNotRateLimited($request);

        // $connection = Container::getDefaultConnection();
        // $authenticated = $connection->auth()->attempt($credentials['samaccountname'], $credentials['password']);

        $username = $request->input('username');

        $credentials = [
            'samaccountname' => $username, // Búsqueda en Active Directory
            'password'       => $request->input('password'),
            'fallback'       => [
                'username' => $username, // Búsqueda en tu tabla users de MySQL
                'password' => $request->input('password'),
            ],
        ];

        try {
            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                RateLimiter::clear($this->throttleKey($request));
                $request->session()->regenerate();

                return redirect()->intended('/home');
            }
        } catch (\Throwable $e) {
            // Capturar cualquier error de base de datos (Ej. restricción NOT NULL en tabla users)
            Log::error('Error durante la sincronización/login de LDAP: ' . $e->getMessage());
            
            // Descomenta la siguiente línea temporalmente para ver el error exacto en pantalla:
            // dd($e->getMessage());
        }



        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($this->throttleKey($request));
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        RateLimiter::hit($this->throttleKey($request));

        return back()->withErrors([
            'username' => __('auth.failed'),
        ])->onlyInput('username');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Throw a throttled validation error once too many failed attempts pile up.
     */
    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), $this->maxAttempts)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'username' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower((string) $request->input('username')).'|'.$request->ip());
    }
}
