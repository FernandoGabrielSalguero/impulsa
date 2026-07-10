<?php

namespace App\Services\Admin;

use App\Models\CorreoLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CorreoLogAdminService
{
    public function list(?string $correo, ?string $asunto, int $perPage = 20): LengthAwarePaginator
    {
        return $this->baseListQuery($correo, $asunto)
            ->orderByDesc('cl.created_at')
            ->orderByDesc('cl.id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function find(int $id): ?CorreoLog
    {
        return CorreoLog::query()
            ->with(['userAuth.info'])
            ->find($id);
    }

    private function baseListQuery(?string $correo, ?string $asunto)
    {
        $query = DB::table('correos_log as cl')
            ->leftJoin('user_auth as ua', 'ua.id', '=', 'cl.user_auth_id')
            ->leftJoin('user_info as ui', 'ui.user_auth_id', '=', 'ua.id')
            ->select([
                'cl.id',
                'cl.user_auth_id',
                'cl.correo',
                'cl.asunto',
                'cl.template',
                'cl.estado',
                'cl.error',
                'cl.created_at',
                'ua.correo as usuario_correo',
                'ui.nombre as usuario_nombre',
                'ui.apellido as usuario_apellido',
                'ui.apodo as usuario_apodo',
            ]);

        $correoFilter = trim((string) $correo);

        if ($correoFilter !== '') {
            $query->where('cl.correo', 'like', '%' . $correoFilter . '%');
        }

        $asuntoFilter = trim((string) $asunto);

        if ($asuntoFilter !== '') {
            $query->where('cl.asunto', 'like', '%' . $asuntoFilter . '%');
        }

        return $query;
    }
}
