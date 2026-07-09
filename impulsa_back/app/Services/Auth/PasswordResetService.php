<?php

namespace App\Services\Auth;

use App\Mail\ResetPasswordMail;
use App\Models\UserAuth;
use App\Services\Mail\ImpulsaMailService;
use App\Support\ImpulsaFrontendUrl;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

class PasswordResetService
{
    public function __construct(
        private readonly ImpulsaMailService $mailService,
    ) {}

    public function buildResetUrl(string $token): string
    {
        return ImpulsaFrontendUrl::to('restablecer-contrasena?token=' . urlencode($token));
    }

    /**
     * @return array{ok: bool, message: string}
     */
    public function requestReset(string $correo): array
    {
        $user = UserAuth::query()
            ->where('correo', $correo)
            ->first();

        if (! $user) {
            throw new InvalidArgumentException('not_found');
        }

        $token = bin2hex(random_bytes(32));
        $expiresAt = now()->addMinutes((int) config('impulsa.password_reset_token_ttl_minutes', 60));

        $user->update([
            'password_reset_token' => $token,
            'password_reset_token_expires_at' => $expiresAt,
            'updated_at' => now(),
        ]);

        $sent = $this->sendResetEmail($user->fresh(), $token);

        return [
            'ok' => $sent,
            'message' => $sent
                ? 'Te enviamos un correo con instrucciones para restablecer tu contraseña.'
                : 'No se pudo enviar el correo de recuperación. Intentá nuevamente en unos minutos.',
        ];
    }

    /**
     * @return array{message: string}
     */
    public function resetPassword(string $token, string $password): array
    {
        if ($token === '' || preg_match('/\A[a-f0-9]{64}\z/i', $token) !== 1) {
            throw new InvalidArgumentException('invalid');
        }

        $user = UserAuth::query()
            ->where('password_reset_token', $token)
            ->first();

        if (! $user || $user->password_reset_token_expires_at === null) {
            throw new InvalidArgumentException('invalid');
        }

        if ($user->password_reset_token_expires_at->isPast()) {
            throw new InvalidArgumentException('expired');
        }

        DB::transaction(function () use ($user, $token, $password): void {
            $updated = UserAuth::query()
                ->whereKey($user->id)
                ->where('password_reset_token', $token)
                ->where('password_reset_token_expires_at', '>', now())
                ->update([
                    'password' => Hash::make($password),
                    'password_reset_token' => null,
                    'password_reset_token_expires_at' => null,
                    'updated_at' => now(),
                ]);

            if ($updated < 1) {
                throw new InvalidArgumentException('invalid');
            }

            $user->tokens()->delete();
        });

        return [
            'message' => 'Tu contraseña fue actualizada. Ya podés iniciar sesión.',
        ];
    }

    private function sendResetEmail(UserAuth $user, string $token): bool
    {
        return $this->mailService->send(
            new ResetPasswordMail($user, $this->buildResetUrl($token))
        );
    }
}
