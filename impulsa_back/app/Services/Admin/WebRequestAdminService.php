<?php

namespace App\Services\Admin;

use App\Models\LandingPageRequest;
use App\Models\LandingPageRequestExternal;
use App\Models\Project;
use App\Models\UserAuth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class WebRequestAdminService
{
    public function listInternal(?string $q, int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->internalBaseQuery($q)
            ->orderByDesc('landing_page_request.created_at')
            ->orderByDesc('landing_page_request.id');

        return $query->paginate($perPage)->withQueryString();
    }

    public function listExternal(?string $q, int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->externalBaseQuery($q)
            ->orderByDesc('landing_page_requests_external.created_at')
            ->orderByDesc('landing_page_requests_external.id');

        return $query->paginate($perPage)->withQueryString();
    }

    public function findInternal(int $id): ?LandingPageRequest
    {
        return $this->internalBaseQuery(null)
            ->where('landing_page_request.id', $id)
            ->first();
    }

    public function findExternal(int $id): ?LandingPageRequestExternal
    {
        return $this->externalBaseQuery(null)
            ->where('landing_page_requests_external.id', $id)
            ->first();
    }

    public function findUserByEmail(string $correo): ?UserAuth
    {
        return UserAuth::query()->where('correo', strtolower(trim($correo)))->first();
    }

    public function findProjectBySource(string $sourceType, int $sourceId): ?Project
    {
        return Project::query()
            ->where('source_type', $sourceType)
            ->where('source_id', $sourceId)
            ->first();
    }

    private function internalBaseQuery(?string $q): Builder
    {
        $query = LandingPageRequest::query()
            ->from('landing_page_request')
            ->join('user_auth as ua', 'ua.id', '=', 'landing_page_request.user_auth_id')
            ->leftJoin('user_info as ui', 'ui.user_auth_id', '=', 'ua.id')
            ->leftJoin('rubro_emprendedor_categoria as rec', 'rec.id', '=', 'landing_page_request.rubro_categoria_id')
            ->leftJoin('rubro_emprendedor_subcategoria as res', 'res.id', '=', 'landing_page_request.rubro_subcategoria_id')
            ->leftJoin('projects as p', function ($join): void {
                $join->on('p.source_id', '=', 'landing_page_request.id')
                    ->where('p.source_type', '=', 'landing_page_request');
            })
            ->select([
                'landing_page_request.*',
                'ua.correo as usuario_correo',
                'ui.nombre as usuario_nombre',
                'ui.apellido as usuario_apellido',
                'ui.apodo as usuario_apodo',
                'rec.nombre as rubro_categoria',
                'res.nombre as rubro_subcategoria',
                'ua.id as cliente_user_id',
                'p.id as proyecto_id',
                'p.status as proyecto_estado',
            ])
            ->with('userAuth.info');

        $search = trim((string) $q);

        if ($search !== '') {
            $like = '%' . $search . '%';
            $query->where(function (Builder $builder) use ($like): void {
                $builder
                    ->where('landing_page_request.nombre_emprendimiento', 'like', $like)
                    ->orWhere('ua.correo', 'like', $like)
                    ->orWhere('ui.nombre', 'like', $like)
                    ->orWhere('ui.apellido', 'like', $like)
                    ->orWhere('ui.apodo', 'like', $like);
            });
        }

        return $query;
    }

    private function externalBaseQuery(?string $q): Builder
    {
        $query = LandingPageRequestExternal::query()
            ->from('landing_page_requests_external')
            ->leftJoin('user_auth as ua', 'ua.correo', '=', 'landing_page_requests_external.correo')
            ->leftJoin('projects as p', function ($join): void {
                $join->on('p.source_id', '=', 'landing_page_requests_external.id')
                    ->where('p.source_type', '=', 'landing_page_requests_external');
            })
            ->select([
                'landing_page_requests_external.*',
                'ua.id as cliente_user_id',
                'ua.rol as cliente_rol',
                'ua.email_verified_at as cliente_email_verified_at',
                'p.id as proyecto_id',
                'p.status as proyecto_estado',
            ]);

        $search = trim((string) $q);

        if ($search !== '') {
            $like = '%' . $search . '%';
            $query->where(function (Builder $builder) use ($like): void {
                $builder
                    ->where('landing_page_requests_external.nombre_proyecto', 'like', $like)
                    ->orWhere('landing_page_requests_external.correo', 'like', $like)
                    ->orWhere('landing_page_requests_external.nombre', 'like', $like);
            });
        }

        return $query;
    }
}
