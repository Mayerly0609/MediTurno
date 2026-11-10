<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use RuntimeException;

class TwoFactorEmailService
{
    public function send(User $user, string $purpose): void
    {
        $this->validatePurpose($purpose);

        $id = $user->getAuthIdentifier();

        $rateKey = "2fa-email-send:{$purpose}:{$id}";

        if (RateLimiter::tooManyAttempts($rateKey, 3)) {
            throw new RuntimeException(
                'Has solicitado demasiados códigos. Inténtalo más tarde.'
            );
        }

        $code = (string) random_int(100000, 999999);

        $cacheKey = "2fa-email-code:{$purpose}:{$id}";

        // Guardar únicamente el hash durante 10 minutos.
        Cache::put(
            $cacheKey,
            hash('sha256', $code),
            now()->addMinutes(10)
        );

        try {
            Mail::raw(
                "Tu código de verificación es: {$code}\n\n"
                . "Vence en 10 minutos. Si no lo solicitaste, "
                . "ignora este mensaje.",
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Código de verificación de seguridad');
                }
            );

            RateLimiter::hit($rateKey, 300);
        } catch (\Throwable $e) {
            Cache::forget($cacheKey);

            throw $e;
        }
    }

    public function verify(
        User $user,
        string $purpose,
        string $code
    ): bool {
        $this->validatePurpose($purpose);

        $id = $user->getAuthIdentifier();

        $attemptKey = "2fa-email-verify:{$purpose}:{$id}";

        if (RateLimiter::tooManyAttempts($attemptKey, 5)) {
            return false;
        }

        RateLimiter::hit($attemptKey, 600);

        $cacheKey = "2fa-email-code:{$purpose}:{$id}";
        $storedHash = Cache::get($cacheKey);

        if (
            ! is_string($storedHash) ||
            ! hash_equals($storedHash, hash('sha256', $code))
        ) {
            return false;
        }

        // Consumir el código después de validarlo.
        Cache::forget($cacheKey);
        RateLimiter::clear($attemptKey);

        return true;
    }

    private function validatePurpose(string $purpose): void
    {
        if (! in_array($purpose, ['activation', 'login'], true)) {
            throw new RuntimeException('Propósito de verificación inválido.');
        }
    }
}
