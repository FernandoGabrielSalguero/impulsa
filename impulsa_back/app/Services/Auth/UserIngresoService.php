<?php

namespace App\Services\Auth;

use App\Models\UserAuth;
use App\Models\UserIngreso;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Throwable;

class UserIngresoService
{
    public function recordLogin(UserAuth $user): void
    {
        try {
            $user->loadMissing('info');
            $now = Carbon::now();

            UserIngreso::query()->create([
                'user_auth_id' => $user->id,
                'nombre_usuario' => $this->resolveUserName($user),
                'rol' => (string) $user->rol,
                'fecha_ingreso' => $now->toDateString(),
                'hora_ingreso' => $now->format('H:i:s'),
            ]);
        } catch (Throwable $exception) {
            Log::warning('No se pudo registrar el ingreso del usuario', [
                'user_auth_id' => $user->id,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    private function resolveUserName(UserAuth $user): string
    {
        $info = $user->info;

        if ($info !== null) {
            $fullName = trim((string) ($info->nombre ?? '') . ' ' . (string) ($info->apellido ?? ''));

            if ($fullName !== '') {
                return $fullName;
            }

            $apodo = trim((string) ($info->apodo ?? ''));

            if ($apodo !== '') {
                return $apodo;
            }
        }

        return trim((string) $user->correo);
    }
}
