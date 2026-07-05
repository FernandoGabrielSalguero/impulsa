<?php

namespace App\Services\Admin;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class AiUsageLogAdminService
{
    public function list(
        ?string $usuario,
        ?string $feature,
        ?string $status,
        ?string $dateFrom,
        ?string $dateTo,
        int $perPage = 20,
    ): LengthAwarePaginator {
        return $this->baseQuery($usuario, $feature, $status, $dateFrom, $dateTo)
            ->orderByDesc('ail.created_at')
            ->orderByDesc('ail.id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function find(int $id): ?object
    {
        return $this->baseQuery(null, null, null, null, null)
            ->where('ail.id', $id)
            ->first();
    }

    /** @return list<string> */
    public function featureOptions(): array
    {
        return DB::table('ai_usage_logs')
            ->whereNotNull('feature')
            ->where('feature', '!=', '')
            ->distinct()
            ->orderBy('feature')
            ->pluck('feature')
            ->map(static fn ($value): string => (string) $value)
            ->all();
    }

    private function baseQuery(
        ?string $usuario,
        ?string $feature,
        ?string $status,
        ?string $dateFrom,
        ?string $dateTo,
    ) {
        $query = DB::table('ai_usage_logs as ail')
            ->leftJoin('user_auth as ua', 'ua.id', '=', 'ail.user_auth_id')
            ->leftJoin('user_info as ui', 'ui.user_auth_id', '=', 'ua.id')
            ->select([
                'ail.*',
                'ua.correo as usuario_correo',
                'ui.nombre as usuario_nombre',
                'ui.apellido as usuario_apellido',
                'ui.apodo as usuario_apodo',
            ]);

        $usuarioFilter = trim((string) $usuario);
        if ($usuarioFilter !== '') {
            $like = '%' . $usuarioFilter . '%';
            $query->where(function ($builder) use ($like): void {
                $builder
                    ->where('ua.correo', 'like', $like)
                    ->orWhere('ui.nombre', 'like', $like)
                    ->orWhere('ui.apellido', 'like', $like)
                    ->orWhere('ui.apodo', 'like', $like)
                    ->orWhereRaw('CAST(ail.user_auth_id AS CHAR) LIKE ?', [$like]);
            });
        }

        $featureFilter = trim((string) $feature);
        if ($featureFilter !== '' && $featureFilter !== '__all__') {
            $query->where('ail.feature', $featureFilter);
        }

        $statusFilter = trim((string) $status);
        if ($statusFilter !== '' && $statusFilter !== '__all__') {
            $query->where('ail.status', $statusFilter);
        }

        $from = trim((string) $dateFrom);
        if ($from !== '') {
            $query->whereDate('ail.created_at', '>=', $from);
        }

        $to = trim((string) $dateTo);
        if ($to !== '') {
            $query->whereDate('ail.created_at', '<=', $to);
        }

        return $query;
    }
}
