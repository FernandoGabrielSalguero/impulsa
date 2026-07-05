<?php

namespace App\Services\Admin;

use App\Models\CorreoLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class CorreoLogAdminService
{
    public function list(?string $correo, ?string $asunto, int $perPage = 20): LengthAwarePaginator
    {
        return $this->baseQuery($correo, $asunto)
            ->orderByDesc('correos_log.created_at')
            ->orderByDesc('correos_log.id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function find(int $id): ?CorreoLog
    {
        return $this->baseQuery(null, null)
            ->where('correos_log.id', $id)
            ->first();
    }

    private function baseQuery(?string $correo, ?string $asunto): Builder
    {
        $query = CorreoLog::query()
            ->from('correos_log')
            ->leftJoin('user_auth as ua', 'ua.id', '=', 'correos_log.user_auth_id')
            ->leftJoin('user_info as ui', 'ui.user_auth_id', '=', 'ua.id')
            ->select([
                'correos_log.*',
                'ua.correo as usuario_correo',
                'ui.nombre as usuario_nombre',
                'ui.apellido as usuario_apellido',
                'ui.apodo as usuario_apodo',
            ])
            ->with('userAuth.info');

        $correoFilter = trim((string) $correo);
        if ($correoFilter !== '') {
            $query->where('correos_log.correo', 'like', '%' . $correoFilter . '%');
        }

        $asuntoFilter = trim((string) $asunto);
        if ($asuntoFilter !== '') {
            $query->where('correos_log.asunto', 'like', '%' . $asuntoFilter . '%');
        }

        return $query;
    }
}
