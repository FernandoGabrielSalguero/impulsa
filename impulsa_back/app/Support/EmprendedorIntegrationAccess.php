<?php

namespace App\Support;

use App\Models\ApiIntegration;
use App\Models\UserAuth;
use Illuminate\Validation\ValidationException;

class EmprendedorIntegrationAccess
{
    public function integrationForUser(UserAuth $user): ?ApiIntegration
    {
        return ApiIntegration::query()
            ->where('user_auth_id', $user->id)
            ->orderByDesc('id')
            ->first();
    }

    public function requireIntegration(UserAuth $user): ApiIntegration
    {
        $integration = $this->integrationForUser($user);

        if ($integration === null) {
            throw ValidationException::withMessages([
                'integration' => ['No tenes una integracion API configurada. Contacta al administrador.'],
            ]);
        }

        if ($integration->status !== 'active') {
            throw ValidationException::withMessages([
                'integration' => ['Tu integracion API no esta activa. Contacta al administrador.'],
            ]);
        }

        return $integration;
    }
}