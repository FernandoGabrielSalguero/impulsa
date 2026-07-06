<?php

namespace App\Support;

use App\Models\ApiIntegration;
use App\Models\UserAuth;
use Illuminate\Validation\ValidationException;

class EmprendedorIntegrationAccess
{
    public function integrationForUser(UserAuth $user): ?ApiIntegration
    {
        $direct = ApiIntegration::query()
            ->where('user_auth_id', $user->id)
            ->orderByDesc('id')
            ->first();

        if ($direct !== null) {
            return $direct;
        }

        if ($user->rol !== 'impulsa_cliente') {
            return null;
        }

        return ApiIntegration::query()
            ->join('projects as p', 'p.project_name', '=', 'api_integrations.project_name')
            ->where('p.client_user_id', $user->id)
            ->where('p.client_visible', 1)
            ->orderByDesc('api_integrations.id')
            ->select('api_integrations.*')
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