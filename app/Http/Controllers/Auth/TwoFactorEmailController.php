<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\TwoFactorAuthenticationProvider;
use Throwable;

class TwoFactorEmailController extends Controller
{
      /**
     * Enviar el código de correo.
     *
     * purpose = activation | login
     */
    public function sendCode(Request $request): JsonResponse
    {
        $request->validate([
            'purpose' => ['required', 'in:activation,login'],
        ]);

        $purpose = $request->input('purpose');
        $user = $this->getUserForPurpose($request, $purpose);

        

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'La sesión no es válida. Intenta nuevamente.',
            ], 403);
        }

        if (!$user->email) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario no tiene un correo configurado.',
            ], 422);
        }

        $rateKey = 'two-factor-email-send:'. $purpose . ':' . $user->getAuthIdentifier();

        if (RateLimiter::tooManyAttempts($rateKey, 3)) {
            return response()->json([
                'success' => false,
                'message' => 'Has solicitado demasiados códigos. Inténtalo más tarde.',
            ], 429);
        }

        $code = (string) random_int(100000, 999999);


        // Guardamos un hash, no el código en texto plano.
        $request->session()->put(
            "two_factor_email.{$purpose}",
            [
                'code_hash' => hash('sha256', $code),
                'expires_at' => now()->addMinutes(2)->timestamp,
                'attempts' => 0,
            ]
        );

        try {

            $asunto = $purpose == 'activation'
            ? 'ACTIVAR VERIFICACIÓN DE DOBLE FACTOR EN PLATAFORMA TI'
            : 'CÓDIGO DE VERIFICACIÓN PARA LA PLATAFORMA TI';


            Mail::send('adminlte.auth.email-two-factor',
                    [
                        'purpose' => $purpose,
                        'code' => $code,
                    ],
                function ($message) use ($user, $asunto) {
                    $message->to($user->email)->subject( $asunto);
                }
            );

            RateLimiter::hit($rateKey, 300);

            return response()->json([
                'success' => true,
                'message' => 'Se ha enviado el código a tu correo electrónico.',
            ]);
        } catch (Throwable $e) {
            $request->session()->forget(
                "two_factor_email.{$purpose}"
            );

            Log::error('Error enviando código 2FA', [
                'exception' => $e,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'No fue posible enviar el correo.',
            ], 500);
        }
    }

    /**
     * Verificar el código de Authenticator o de correo.
     */
    public function verifyCode(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'purpose' => ['required', 'in:activation,login'],
            'code' => ['required', 'digits:6'],
        ]);

        $purpose = $validated['purpose'];
        $code = $validated['code'];

        $user = $this->getUserForPurpose($request, $purpose);

        if (!$user) {
            return redirect()->route('login')->withErrors([
                'code' => 'La sesión de verificación ha expirado.',
            ]);
        }

        // El secreto debe existir antes de validar cualquiera
        // de los métodos de doble factor.
        if (!$user->two_factor_secret) {
            return back()->withErrors([
                'code' => 'Debes configurar primero el doble factor.',
            ]);
        }

        // Durante el inicio de sesión, solo se permite utilizar
        // el segundo factor si ya fue confirmado.
        if ($purpose === 'login' && !$user->two_factor_confirmed_at) {
            return redirect()->route('login')->withErrors([
                'code' => 'El doble factor no está confirmado.',
            ]);
        }

        $valid = $this->isValidAuthenticatorCode($user, $code);

        // Si no es TOTP, comprobamos el código enviado por correo.
        if (!$valid) {
            $valid = $this->isValidEmailCode(
                $request,
                $purpose,
                $code
            );
        }

        if (!$valid) {
            return back()->withErrors([
                'code' => 'El código es incorrecto, expiró o excediste los intentos.',
            ])->withInput($request->only('purpose'));
        }

        // El código de correo ya no debe poder reutilizarse.
        $request->session()->forget(
            "two_factor_email.{$purpose}"
        );

        if ($purpose === 'activation') {
            // Solo confirmamos la activación después de validar
            // correctamente el TOTP o el código de correo.
            $user->forceFill([
                'two_factor_confirmed_at' => now(),
            ])->save();

            return redirect()->intended(config('fortify.home'))
                ->with('success', 'El doble factor fue confirmado correctamente.');
        }

        // Inicio de sesión: completar la autenticación.
        $remember = (bool) $request->session()->get(
            'login.remember',
            false
        );

        Auth::guard('web')->login($user, $remember);

        $request->session()->regenerate();

        $request->session()->forget([
            'login.id',
            'login.remember',
        ]);

        return redirect()->intended(config('fortify.home'));
    }

    /**
     * Determinar el usuario según el propósito.
     */
    private function getUserForPurpose(Request $request,string $purpose ): ?User {
        if ($purpose === 'activation') {
            // La activación requiere un usuario autenticado.
            return $request->user();
        }

        // En el desafío de Fortify el usuario aún no ha
        // completado la autenticación.
        $userId = $request->session()->get('login.id');

        if (!$userId) {
            return null;
        }

        return User::query()->find($userId);
    }

    /**
     * Validar TOTP mediante el proveedor de Fortify.
     */
    private function isValidAuthenticatorCode(
        User $user,
        string $code
    ): bool {
        try {
            $secret = decrypt($user->two_factor_secret);

            return app(TwoFactorAuthenticationProvider::class)
                ->verify($secret, $code);
        } catch (Throwable $e) {
            Log::warning('No fue posible validar el código TOTP.', [
                'user_id' => $user->getAuthIdentifier(),
            ]);

            return false;
        }
    }

    /**
     * Validar el código de correo almacenado en la sesión.
     */
    private function isValidEmailCode(
        Request $request,
        string $purpose,
        string $code
    ): bool {
        $key = "two_factor_email.{$purpose}";
        $data = $request->session()->get($key);

        if (!is_array($data)) {
            return false;
        }

        if (
            empty($data['code_hash']) ||
            empty($data['expires_at']) ||
            now()->timestamp > $data['expires_at']
        ) {
            $request->session()->forget($key);

            return false;
        }

        // Limitar los intentos de verificación en esta sesión.
        $attempts = (int) ($data['attempts'] ?? 0);

        if ($attempts >= 5) {
            $request->session()->forget($key);

            return false;
        }

        $data['attempts'] = $attempts + 1;
        $request->session()->put($key, $data);

        return hash_equals(
            $data['code_hash'],
            hash('sha256', $code)
        );
    }
}