<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmailMarketingContactCollection;
use App\Services\Admin\EmailMarketingContactsService;
use App\Support\RoleLabels;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmailMarketingContactsController extends Controller
{
    public function __construct(
        private readonly EmailMarketingContactsService $emailMarketingContactsService,
    ) {}

    public function index(Request $request): EmailMarketingContactCollection
    {
        $perPage = max(1, min((int) $request->integer('per_page', 15), 100));
        $contacts = $this->emailMarketingContactsService->list(
            $this->filtersFromRequest($request),
            $perPage,
        );

        return new EmailMarketingContactCollection($contacts);
    }

    public function export(Request $request): StreamedResponse
    {
        return $this->emailMarketingContactsService->exportCsv(
            $this->filtersFromRequest($request),
        );
    }

    /** @return array<string, mixed> */
    private function filtersFromRequest(Request $request): array
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'rol' => ['nullable', 'string', 'in:' . implode(',', array_keys(RoleLabels::all()))],
            'usuario_tipo' => ['nullable', 'string', 'in:interno,externo'],
            'only_opt_in' => ['nullable', 'boolean'],
            'only_verified' => ['nullable', 'boolean'],
            'ids' => ['nullable', 'string', 'max:5000'],
        ]);

        $ids = collect(explode(',', (string) $request->query('ids', '')))
            ->map(static fn (string $id): int => (int) trim($id))
            ->filter(static fn (int $id): bool => $id > 0)
            ->unique()
            ->values()
            ->all();

        return [
            'q' => $request->query('q'),
            'rol' => $request->query('rol'),
            'usuario_tipo' => $request->query('usuario_tipo'),
            'only_opt_in' => $request->boolean('only_opt_in', true),
            'only_verified' => $request->boolean('only_verified', true),
            'ids' => $ids,
        ];
    }
}
