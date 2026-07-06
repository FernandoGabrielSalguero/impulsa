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

        $result = $this->inboxService->listForUser(
            $user,
            $request->query('q'),
            $request->query('state'),
            (int) $request->query('per_page', 20),
        );

        return response()->json($result);
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
