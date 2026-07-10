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
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function find(int $id): ?CorreoLog
    {
        return $this->baseQuery(null, null)
            ->where('id', $id)
            ->first();
    }

    private function baseQuery(?string $correo, ?string $asunto): Builder
    {
        $query = CorreoLog::query()
            ->with(['userAuth.info']);

        $correoFilter = trim((string) $correo);

        if ($correoFilter !== '') {
            $query->where('correo', 'like', '%' . $correoFilter . '%');
        }

        $asuntoFilter = trim((string) $asunto);

        if ($asuntoFilter !== '') {
            $query->where('asunto', 'like', '%' . $asuntoFilter . '%');
        }

        return $query;
    }
}
