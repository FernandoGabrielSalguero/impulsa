<?php

namespace App\Services\Admin;

use App\Models\ApiIntegration;
use App\Models\UserAuth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApiIntegrationAdminService
{
    public function syncOwners(): void
    {
        DB::statement("
            UPDATE api_integrations ai
            LEFT JOIN (
                SELECT project_name, MIN(client_user_id) AS client_user_id
                FROM projects
                WHERE client_user_id IS NOT NULL
                  AND TRIM(COALESCE(project_name, '')) <> ''
                GROUP BY project_name
            ) p ON p.project_name = ai.project_name
            LEFT JOIN (
                SELECT nombre_emprendimiento, MIN(user_auth_id) AS user_auth_id
                FROM landing_page_request
                WHERE user_auth_id IS NOT NULL
                  AND TRIM(COALESCE(nombre_emprendimiento, '')) <> ''
                GROUP BY nombre_emprendimiento
            ) lpr ON lpr.nombre_emprendimiento = ai.project_name
            SET ai.user_auth_id = COALESCE(p.client_user_id, lpr.user_auth_id),
                ai.updated_at = NOW()
            WHERE ai.user_auth_id IS NULL
              AND COALESCE(p.client_user_id, lpr.user_auth_id) IS NOT NULL
        ");
    }

    /** @return array{data: LengthAwarePaginator} */
    public function list(?string $q, ?string $status, int $perPage = 20): array
    {
        $this->syncOwners();

        $query = $this->baseListQuery()
            ->orderByDesc('ai.updated_at')
            ->orderByDesc('ai.id');

        $search = trim((string) $q);

        if (mb_strlen($search) >= 3) {
            $like = '%' . $search . '%';
            $query->where(function ($builder) use ($like): void {
                $builder
                    ->where('ai.project_name', 'like', $like)
                    ->orWhere('ai.allowed_domain', 'like', $like)
                    ->orWhere('ai.public_key', 'like', $like)
                    ->orWhere('ua.correo', 'like', $like)
                    ->orWhereRaw('CAST(ai.id AS CHAR) LIKE ?', [$like]);
            });
        }

        $statusFilter = trim((string) $status);

        if ($statusFilter !== '' && $statusFilter !== '__all__') {
            $query->where('ai.status', $statusFilter);
        }

        return [
            'data' => $query->paginate($perPage)->withQueryString(),
        ];
    }

    public function find(int $integrationId): ApiIntegration
    {
        $this->syncOwners();

        $row = $this->baseListQuery()->where('ai.id', $integrationId)->first();

        if ($row === null) {
            throw ValidationException::withMessages([
                'integration' => ['La integración no existe.'],
            ]);
        }

        return $row;
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): array
    {
        $projectName = trim((string) $data['project_name']);
        $allowedDomain = $this->normalizeDomain((string) $data['allowed_domain']);
        $ownerId = $this->resolveOwnerId(
            isset($data['user_auth_id']) ? (int) $data['user_auth_id'] : null,
            $projectName,
            required: true,
        );

        $publicKey = $this->generateUniquePublicKey();
        $secretPlain = $this->generatePlainKey('sk_');

        $integration = ApiIntegration::query()->create([
            'project_name' => $projectName,
            'allowed_domain' => $allowedDomain,
            'public_key' => $publicKey,
            'secret_key_hash' => password_hash($secretPlain, PASSWORD_DEFAULT),
            'status' => 'active',
            'user_auth_id' => $ownerId,
        ]);

        return [
            'integration' => $this->find((int) $integration->id),
            'secret_key' => $secretPlain,
        ];
    }

    /** @param array<string, mixed> $data */
    public function update(ApiIntegration $integration, array $data): ApiIntegration
    {
        $projectName = trim((string) $data['project_name']);
        $allowedDomain = $this->normalizeDomain((string) $data['allowed_domain']);
        $ownerId = $this->resolveOwnerId(
            array_key_exists('user_auth_id', $data) && $data['user_auth_id'] !== null
                ? (int) $data['user_auth_id']
                : null,
            $projectName,
            required: false,
        );

        $integration->update([
            'project_name' => $projectName,
            'allowed_domain' => $allowedDomain,
            'user_auth_id' => $ownerId,
        ]);

        return $this->find((int) $integration->id);
    }

    public function toggleStatus(ApiIntegration $integration): ApiIntegration
    {
        $integration->update([
            'status' => $integration->status === 'active' ? 'inactive' : 'active',
        ]);

        return $this->find((int) $integration->id);
    }

    /** @return array{integration: ApiIntegration, public_key: string} */
    public function regeneratePublicKey(ApiIntegration $integration): array
    {
        $publicKey = $this->generateUniquePublicKey((int) $integration->id);

        $integration->update(['public_key' => $publicKey]);

        return [
            'integration' => $this->find((int) $integration->id),
            'public_key' => $publicKey,
        ];
    }

    /** @return array{integration: ApiIntegration, secret_key: string} */
    public function regenerateSecretKey(ApiIntegration $integration): array
    {
        $secretPlain = $this->generatePlainKey('sk_');

        $integration->update([
            'secret_key_hash' => password_hash($secretPlain, PASSWORD_DEFAULT),
        ]);

        return [
            'integration' => $this->find((int) $integration->id),
            'secret_key' => $secretPlain,
        ];
    }

    /** @return Collection<int, array<string, mixed>> */
    public function listOwners(): Collection
    {
        return DB::table('user_auth as ua')
            ->leftJoin('user_contacto as uc', 'uc.user_auth_id', '=', 'ua.id')
            ->leftJoin('user_info as ui', 'ui.user_auth_id', '=', 'ua.id')
            ->orderByRaw('COALESCE(NULLIF(TRIM(ui.nombre), ""), NULLIF(TRIM(ui.apodo), ""), ua.correo) ASC')
            ->orderBy('ua.id')
            ->get([
                'ua.id',
                'ua.correo',
                'ua.rol',
                'uc.correo as contacto_correo',
                'ui.nombre',
                'ui.apellido',
                'ui.apodo',
            ])
            ->map(static function ($user): array {
                $name = trim((string) (($user->nombre ?? '') . ' ' . ($user->apellido ?? '')));

                if ($name === '') {
                    $name = trim((string) ($user->apodo ?? ''));
                }

                $email = trim((string) ($user->contacto_correo ?? $user->correo ?? ''));

                return [
                    'id' => (int) $user->id,
                    'correo' => $user->correo,
                    'rol' => $user->rol,
                    'nombre' => $name !== '' ? $name : null,
                    'label' => $name !== '' ? $name . ' · ' . $email : $email,
                ];
            });
    }

    /** @return list<array{nombre: string, origen: string}> */
    public function listProjectOptions(): array
    {
        $rows = DB::select("
            SELECT nombre, origen, fecha_referencia
            FROM (
                SELECT DISTINCT TRIM(p.project_name) AS nombre, 'Proyecto' AS origen, p.updated_at AS fecha_referencia
                FROM projects p
                WHERE TRIM(COALESCE(p.project_name, '')) <> ''

                UNION ALL

                SELECT DISTINCT TRIM(lpr.nombre_emprendimiento) AS nombre, 'Solicitud interna' AS origen, lpr.updated_at AS fecha_referencia
                FROM landing_page_request lpr
                WHERE TRIM(COALESCE(lpr.nombre_emprendimiento, '')) <> ''

                UNION ALL

                SELECT DISTINCT TRIM(lpre.nombre_proyecto) AS nombre, 'Solicitud externa' AS origen, lpre.created_at AS fecha_referencia
                FROM landing_page_requests_external lpre
                WHERE TRIM(COALESCE(lpre.nombre_proyecto, '')) <> ''
            ) opciones
            ORDER BY nombre ASC, fecha_referencia DESC
        ");

        $options = [];
        $seen = [];

        foreach ($rows as $row) {
            $name = trim((string) ($row->nombre ?? ''));

            if ($name === '') {
                continue;
            }

            $key = mb_strtolower($name);

            if (isset($seen[$key])) {
                continue;
            }

            $seen[$key] = true;
            $options[] = [
                'nombre' => $name,
                'origen' => (string) ($row->origen ?? ''),
            ];
        }

        return $options;
    }

    public function resolveOwnerFromProject(string $projectName): ?int
    {
        $projectName = trim($projectName);

        if ($projectName === '') {
            return null;
        }

        $projectOwner = DB::table('projects')
            ->whereNotNull('client_user_id')
            ->where('project_name', $projectName)
            ->min('client_user_id');

        if ($projectOwner !== null) {
            return (int) $projectOwner;
        }

        $requestOwner = DB::table('landing_page_request')
            ->whereNotNull('user_auth_id')
            ->where('nombre_emprendimiento', $projectName)
            ->min('user_auth_id');

        return $requestOwner !== null ? (int) $requestOwner : null;
    }

    private function baseListQuery()
    {
        return ApiIntegration::query()
            ->from('api_integrations as ai')
            ->leftJoin('user_auth as ua', 'ua.id', '=', 'ai.user_auth_id')
            ->leftJoin('user_contacto as uc', 'uc.user_auth_id', '=', 'ua.id')
            ->leftJoin('user_info as ui', 'ui.user_auth_id', '=', 'ua.id')
            ->leftJoin(DB::raw('(
                SELECT api_integration_id, COUNT(*) AS total_visits
                FROM visit_user_page
                GROUP BY api_integration_id
            ) v'), 'v.api_integration_id', '=', 'ai.id')
            ->leftJoin(DB::raw('(
                SELECT api_integration_id, COUNT(*) AS total_contacts
                FROM forms_clients_contact
                GROUP BY api_integration_id
            ) f'), 'f.api_integration_id', '=', 'ai.id')
            ->select([
                'ai.*',
                'ua.correo as owner_auth_correo',
                'uc.correo as owner_contacto_correo',
                'ui.nombre as owner_nombre',
                'ui.apellido as owner_apellido',
                'ui.apodo as owner_apodo',
                DB::raw('COALESCE(v.total_visits, 0) as total_visits'),
                DB::raw('COALESCE(f.total_contacts, 0) as total_contacts'),
            ]);
    }

    private function normalizeDomain(string $domain): string
    {
        $domain = trim($domain);

        if ($domain === '') {
            throw ValidationException::withMessages([
                'allowed_domain' => ['El dominio autorizado es obligatorio.'],
            ]);
        }

        if (! preg_match('#^https?://#i', $domain)) {
            $domain = 'https://' . $domain;
        }

        $parts = parse_url($domain);

        if ($parts === false || empty($parts['scheme']) || empty($parts['host'])) {
            throw ValidationException::withMessages([
                'allowed_domain' => ['El dominio autorizado no tiene un formato válido.'],
            ]);
        }

        $normalized = strtolower($parts['scheme']) . '://' . strtolower($parts['host']);

        if (! empty($parts['port'])) {
            $normalized .= ':' . (int) $parts['port'];
        }

        return $normalized;
    }

    private function resolveOwnerId(?int $ownerId, string $projectName, bool $required): ?int
    {
        if ($ownerId !== null && $ownerId > 0) {
            if (! UserAuth::query()->whereKey($ownerId)->exists()) {
                throw ValidationException::withMessages([
                    'user_auth_id' => ['El dueño seleccionado no es válido.'],
                ]);
            }

            return $ownerId;
        }

        $inferred = $this->resolveOwnerFromProject($projectName);

        if ($inferred !== null) {
            return $inferred;
        }

        if ($required) {
            throw ValidationException::withMessages([
                'user_auth_id' => ['Seleccioná un dueño para la integración o usá un proyecto con propietario asociado.'],
            ]);
        }

        return null;
    }

    private function generatePlainKey(string $prefix): string
    {
        return $prefix . bin2hex(random_bytes(16));
    }

    private function generateUniquePublicKey(?int $excludeId = null): string
    {
        do {
            $key = $this->generatePlainKey('pk_');
        } while ($this->publicKeyExists($key, $excludeId));

        return $key;
    }

    private function publicKeyExists(string $publicKey, ?int $excludeId = null): bool
    {
        $query = ApiIntegration::query()->where('public_key', $publicKey);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
