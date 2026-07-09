<?php

namespace App\PublicEmbed\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\PublicEmbed\Services\ContactSubmissionInboxService;
use App\PublicEmbed\Support\PublicResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactSubmissionController extends Controller
{
    public function __construct(
        private readonly ContactSubmissionInboxService $inboxService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = max(1, min((int) $request->integer('per_page', 20), 100));
        $paginator = $this->inboxService->listForAdmin(
            $request->query('q'),
            $request->query('integration_id') !== null ? (int) $request->query('integration_id') : null,
            $request->query('state'),
            $perPage,
            (int) $request->integer('page', 1),
        );

        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function show(int $contactSubmission): JsonResponse
    {
        return PublicResponse::success($this->inboxService->findForAdmin($contactSubmission));
    }

    public function updateState(Request $request, int $contactSubmission): JsonResponse
    {
        $validated = $request->validate([
            'state' => ['required', 'string', 'in:recibido,cancelado,aprobado'],
        ]);

        return PublicResponse::success(
            $this->inboxService->updateStateForAdmin($contactSubmission, $validated['state']),
        );
    }

    public function destroy(int $contactSubmission): JsonResponse
    {
        $this->inboxService->deleteForAdmin($contactSubmission);

        return response()->json([
            'message' => 'Contacto eliminado correctamente.',
        ]);
    }
}
