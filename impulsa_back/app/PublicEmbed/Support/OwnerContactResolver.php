<?php

namespace App\PublicEmbed\Support;

use App\Models\ApiIntegration;
use Illuminate\Support\Facades\DB;

final class OwnerContactResolver
{
    /** @return array{name: string|null, email: string|null, whatsapp: string|null} */
    public function resolve(ApiIntegration $integration): array
    {
        $userId = $integration->user_auth_id;

        if ($userId === null) {
            return [
                'name' => null,
                'email' => null,
                'whatsapp' => null,
            ];
        }

        $info = DB::table('user_info')
            ->where('user_auth_id', $userId)
            ->first();

        $contact = DB::table('user_contacto')
            ->where('user_auth_id', $userId)
            ->first();

        $auth = DB::table('user_auth')
            ->where('id', $userId)
            ->first();

        $nameParts = array_filter([
            trim((string) ($info->nombre ?? '')),
            trim((string) ($info->apellido ?? '')),
        ]);

        $name = $nameParts !== [] ? implode(' ', $nameParts) : null;

        if ($name === null || $name === '') {
            $name = trim((string) ($info->apodo ?? '')) ?: null;
        }

        return [
            'name' => $name,
            'email' => $auth->correo ?? null,
            'whatsapp' => $contact->whatsapp ?? null,
        ];
    }
}
