<?php

namespace App\PublicEmbed\Services;

use App\Models\ApiIntegration;
use App\Models\UserAuth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ContactSubmissionInboxService
{
    /** @return array{data: LengthAwarePaginator<int, object>} */
    public function listForAdmin(?string $q, ?int $integrationId, ?string $state, int $perPage = 20): array
    {
        $query = $this->baseQuery()
            ->orderByDesc('f.created_at')
            ->orderByDesc('f.id');

        $this->applyFilters($query, $q, $integrationId, $state);

        return ['data' => $query->paginate($perPage)->withQueryString()];
    }

    /** @return array{data: LengthAwarePaginator<int, object>} */
    public function listForUser(UserAuth $user, ?string $q, ?string $state, int $perPage = 20): array
    {
        $integrationIds = $this->integrationIdsForUser($user);

        if ($integrationIds === []) {
            return ['data' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, $perPage)];
        }

        $query = $this->baseQuery()
            ->whereIn('f.api_integration_id', $integrationIds)
            ->orderByDesc('f.created_at')
            ->orderByDesc('f.id');

        $this->applyFilters($query, $q, null, $state);

        return ['data' => $query->paginate($perPage)->withQueryString()];
    }

    public function findForAdmin(int $id): object
    {
        $row = $this->baseQuery()->where('f.id', $id)->first();

        if ($row === null) {
            throw ValidationException::withMessages([
                'contact_submission' => ['Contacto no encontrado.'],
            ]);
        }

        return $row;
    }

    public function findForUser(UserAuth $user, int $id): object
    {
        $integrationIds = $this->integrationIdsForUser($user);

        $row = $this->baseQuery()
            ->where('f.id', $id)
            ->whereIn('f.api_integration_id', $integrationIds)
            ->first();

        if ($row === null) {
            throw ValidationException::withMessages([
                'contact_submission' => ['Contacto no encontrado.'],
            ]);
        }

        return $row;
    }

    public function updateStateForAdmin(int $id, string $state): object
    {
        $this->assertValidState($state);

        $updated = DB::table('forms_clients_contact')
            ->where('id', $id)
            ->update([
                'state' => $state,
                'updated_at' => now(),
            ]);

        if ($updated === 0) {
            throw ValidationException::withMessages([
                'contact_submission' => ['Contacto no encontrado.'],
            ]);
        }

        return $this->findForAdmin($id);
    }

    public function updateStateForUser(UserAuth $user, int $id, string $state): object
    {
        $this->assertValidState($state);
        $this->findForUser($user, $id);

        DB::table('forms_clients_contact')
            ->where('id', $id)
            ->update([
                'state' => $state,
                'updated_at' => now(),
            ]);

        return $this->findForUser($user, $id);
    }

    /** @return list<int> */
    private function integrationIdsForUser(UserAuth $user): array
    {
        if ($user->rol === 'impulsa_administrador') {
            return DB::table('api_integrations')->pluck('id')->map(fn ($id): int => (int) $id)->all();
        }

        if ($user->rol === 'impulsa_emprendedor') {
            return DB::table('api_integrations')
                ->where('user_auth_id', $user->id)
                ->pluck('id')
                ->map(fn ($id): int => (int) $id)
                ->all();
        }

        if ($user->rol === 'impulsa_cliente') {
            return DB::table('api_integrations as ai')
                ->join('projects as p', 'p.project_name', '=', 'ai.project_name')
                ->where('p.client_user_id', $user->id)
                ->where('p.client_visible', 1)
                ->pluck('ai.id')
                ->map(fn ($id): int => (int) $id)
                ->all();
        }

        return [];
    }

    private function baseQuery(): \Illuminate\Database\Query\Builder
    {
        return DB::table('forms_clients_contact as f')
            ->leftJoin('api_integrations as ai', 'ai.id', '=', 'f.api_integration_id')
            ->select([
                'f.id',
                'f.page',
                'f.api_integration_id',
                'f.contact_nombre',
                'f.contact_whatsapp',
                'f.contact_email',
                'f.contact_description',
                'f.contact_consultation',
                'f.state',
                'f.created_at',
                'f.updated_at',
                'ai.project_name as integration_project_name',
                'ai.allowed_domain as integration_domain',
            ]);
    }

    private function applyFilters(\Illuminate\Database\Query\Builder $query, ?string $q, ?int $integrationId, ?string $state): void
    {
        if ($integrationId !== null && $integrationId > 0) {
            $query->where('f.api_integration_id', $integrationId);
        }

        $stateFilter = trim((string) $state);

        if ($stateFilter !== '' && $stateFilter !== '__all__') {
            $query->where('f.state', $stateFilter);
        }

        $search = trim((string) $q);

        if (mb_strlen($search) >= 2) {
            $like = '%' . $search . '%';
            $query->where(function ($builder) use ($like): void {
                $builder
                    ->where('f.contact_nombre', 'like', $like)
                    ->orWhere('f.contact_email', 'like', $like)
                    ->orWhere('f.contact_whatsapp', 'like', $like)
                    ->orWhere('f.page', 'like', $like)
                    ->orWhere('ai.project_name', 'like', $like);
            });
        }
    }

    private function assertValidState(string $state): void
    {
        if (! in_array($state, ['recibido', 'cancelado', 'aprobado'], true)) {
            throw ValidationException::withMessages([
                'state' => ['Estado inválido.'],
            ]);
        }
    }
}
