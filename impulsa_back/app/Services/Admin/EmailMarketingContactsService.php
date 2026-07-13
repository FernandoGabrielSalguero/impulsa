<?php

namespace App\Services\Admin;

use App\Models\UserAuth;
use App\Support\RoleLabels;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmailMarketingContactsService
{
    /** @param array<string, mixed> $filters */
    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->buildQuery($filters)
            ->paginate($perPage)
            ->withQueryString();
    }

    /** @param array<string, mixed> $filters */
    public function exportCsv(array $filters): StreamedResponse
    {
        $filename = 'impulsa-contactos-reach-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($filters): void {
            $handle = fopen('php://output', 'wb');

            if ($handle === false) {
                return;
            }

            // UTF-8 BOM for Excel / Hostinger import tools.
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['email', 'name', 'surname', 'note'], ',', '"', '\\');

            $this->buildQuery($filters)
                ->orderBy('user_auth.id')
                ->chunk(200, function ($users) use ($handle): void {
                    foreach ($users as $user) {
                        fputcsv(
                            $handle,
                            $this->contactRow($user),
                            ',',
                            '"',
                            '\\',
                        );
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /** @param array<string, mixed> $filters */
    private function buildQuery(array $filters): Builder
    {
        $query = UserAuth::query()
            ->with(['info', 'contacto'])
            ->leftJoin('user_info as ui', 'ui.user_auth_id', '=', 'user_auth.id')
            ->leftJoin('user_contacto as uc', 'uc.user_auth_id', '=', 'user_auth.id')
            ->select('user_auth.*')
            ->orderByDesc('user_auth.created_at')
            ->orderByDesc('user_auth.id');

        if ($filters['only_opt_in'] ?? true) {
            $query->where(function (Builder $builder): void {
                $builder
                    ->where('uc.permison_correo', true)
                    ->orWhereNull('uc.permison_correo');
            });
        }

        if ($filters['only_verified'] ?? true) {
            $query->whereNotNull('user_auth.email_verified_at');
        }

        if (! empty($filters['rol'])) {
            $query->where('user_auth.rol', $filters['rol']);
        }

        if (! empty($filters['usuario_tipo'])) {
            $query->where('user_auth.usuario_tipo', $filters['usuario_tipo']);
        }

        $ids = $filters['ids'] ?? [];

        if ($ids !== []) {
            $query->whereIn('user_auth.id', $ids);
        }

        $search = trim((string) ($filters['q'] ?? ''));

        if (mb_strlen($search) >= 4) {
            $query->where(function (Builder $builder) use ($search): void {
                $like = '%' . $search . '%';

                $builder
                    ->where('user_auth.correo', 'like', $like)
                    ->orWhere('uc.correo', 'like', $like)
                    ->orWhere('ui.nombre', 'like', $like)
                    ->orWhere('ui.apellido', 'like', $like)
                    ->orWhere('ui.apodo', 'like', $like)
                    ->orWhereRaw("CONCAT_WS(' ', ui.nombre, ui.apellido) LIKE ?", [$like]);
            });
        }

        return $query;
    }

    /** @return list<string> */
    private function contactRow(UserAuth $user): array
    {
        $email = trim((string) ($user->correo ?: $user->contacto?->correo));
        $nombre = trim((string) ($user->info?->nombre ?? ''));
        $apellido = trim((string) ($user->info?->apellido ?? ''));

        if ($nombre === '' && filled($user->info?->apodo)) {
            $nombre = trim((string) $user->info?->apodo);
        }

        $noteParts = array_filter([
            RoleLabels::labelFor((string) $user->rol),
            $user->usuario_tipo !== 'externo' ? ucfirst((string) $user->usuario_tipo) : null,
        ]);

        return [
            $email,
            $nombre,
            $apellido,
            implode(' · ', $noteParts),
        ];
    }
}
