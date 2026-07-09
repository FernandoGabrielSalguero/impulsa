<?php

namespace App\PublicEmbed\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\UserAuth;
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
        /** @var UserAuth $user */
        $user = $request->user();

        $perPage = max(1, min((int) $request->integer('per_page', 20), 100));
        $paginator = $this->inboxService->listForUser(
            $user,
            $request->query('q'),
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

    public function show(Request $request, int $contactSubmission): JsonResponse
    {
        /** @var UserAuth $user */
        $user = $request->user();

        return PublicResponse::success(
            $this->inboxService->findForUser($user, $contactSubmission),
        );
    }

    public function updateState(Request $request, int $contactSubmission): JsonResponse
    {
        /** @var UserAuth $user */
        $user = $request->user();

        $validated = $request->validate([
            'state' => ['required', 'string', 'in:recibido,cancelado,aprobado'],
        ]);

        return PublicResponse::success(
            $this->inboxService->updateStateForUser($user, $contactSubmission, $validated['state']),
        );
    }

    public function destroy(Request $request, int $contactSubmission): JsonResponse
    {
        /** @var UserAuth $user */
        $user = $request->user();

        $this->inboxService->deleteForUser($user, $contactSubmission);

        return response()->json([
            'message' => 'Contacto eliminado correctamente.',
        ]);
    }
}
