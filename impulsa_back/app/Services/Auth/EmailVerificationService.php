<?php

namespace App\Services\Auth;

use App\Mail\VerifyEmailMail;
use App\Models\UserAuth;
use App\Models\UserContacto;
use App\Services\Mail\ImpulsaMailService;
use App\Support\ImpulsaFrontendUrl;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class EmailVerificationService
{
    public function __construct(
        private readonly ImpulsaMailService $mailService,
    ) {}

    public function buildVerificationUrl(string $token): string
    {
        return ImpulsaFrontendUrl::to('verificar-correo?token=' . urlencode($token));
    }

    public function sendVerificationEmail(UserAuth $user, string $token): bool
    {
        return $this->mailService->send(
            new VerifyEmailMail($user, $this->buildVerificationUrl($token))
        );
    }

    /**
     * @return array{ok: bool, message: string}
     */
    public function resendVerificationEmail(UserAuth $user): array
    {
        if ($user->email_verified_at !== null) {
            return [
                'ok' => false,
                'message' => 'La cuenta ya tiene el correo verificado.',
            ];
        }

        $token = bin2hex(random_bytes(32));

        $user->update([
            'verification_token' => $token,
            'updated_at' => now(),
        ]);

        $sent = $this->sendVerificationEmail($user->fresh(), $token);

        return [
            'ok' => $sent,
            'message' => $sent
                ? 'Correo de verificación reenviado correctamente.'
                : 'No se pudo reenviar el correo de verificación.',
        ];
    }

    public function verifyToken(string $token): array
    {
        if ($token === '' || preg_match('/\A[a-f0-9]{64}\z/i', $token) !== 1) {
            throw new InvalidArgumentException('invalid');
        }

        $user = UserAuth::query()
            ->where('verification_token', $token)
            ->first();

        if (! $user) {
            throw new InvalidArgumentException('invalid');
        }

        if ($user->email_verified_at !== null) {
            return [
                'status' => 'already_verified',
                'message' => 'La dirección de email de esta cuenta ya fue confirmada anteriormente. Ya podés ingresar a la plataforma.',
            ];
        }

        DB::transaction(function () use ($user, $token): void {
            $updated = UserAuth::query()
                ->whereKey($user->id)
                ->where('verification_token', $token)
                ->whereNull('email_verified_at')
                ->update([
                    'email_verified_at' => now(),
                    'verification_token' => null,
                    'updated_at' => now(),
                ]);

            if ($updated < 1) {
                throw new InvalidArgumentException('invalid');
            }

            UserContacto::query()->where('user_auth_id', $user->id)->update([
                'check_correo' => true,
                'updated_at' => now(),
            ]);
        });

        return [
            'status' => 'verified',
            'message' => 'Tu dirección de email fue confirmada correctamente. Ya podés ingresar a la plataforma.',
        ];
    }
}
