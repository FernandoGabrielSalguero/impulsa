<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Notifications\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        private readonly NotificationService $notificationService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $limit = (int) $request->integer('limit', 30);

        return response()->json([
            'data' => $this->notificationService->listForUser((int) $request->user()->id, $limit),
            'unread_count' => $this->notificationService->unreadCount((int) $request->user()->id),
        ]);
    }

    public function unreadCount(Request $request): JsonResponse
    {
        return response()->json([
            'unread_count' => $this->notificationService->unreadCount((int) $request->user()->id),
        ]);
    }

    public function markRead(Request $request, int $notification): JsonResponse
    {
        $item = $this->notificationService->markRead((int) $request->user()->id, $notification);

        return response()->json([
            'message' => 'Notificación marcada como leída.',
            'data' => $item,
            'unread_count' => $this->notificationService->unreadCount((int) $request->user()->id),
        ]);
    }

    public function markAllRead(Request $request): JsonResponse
    {
        $updated = $this->notificationService->markAllRead((int) $request->user()->id);

        return response()->json([
            'message' => 'Notificaciones marcadas como leídas.',
            'updated' => $updated,
            'unread_count' => 0,
        ]);
    }

    public function dismiss(Request $request, int $notification): JsonResponse
    {
        $this->notificationService->dismiss((int) $request->user()->id, $notification);

        return response()->json([
            'message' => 'Notificación eliminada.',
            'unread_count' => $this->notificationService->unreadCount((int) $request->user()->id),
        ]);
    }
}
