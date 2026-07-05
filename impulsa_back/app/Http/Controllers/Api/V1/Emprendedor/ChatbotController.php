<?php

namespace App\Http\Controllers\Api\V1\Emprendedor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Emprendedor\SyncChatbotNodesRequest;
use App\Http\Requests\Emprendedor\UpdateChatbotSettingsRequest;
use App\Http\Requests\Emprendedor\UpdateChatbotStatusRequest;
use App\Http\Resources\EmprendedorChatbotResource;
use App\Services\Emprendedor\EmprendedorChatbotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ChatbotController extends Controller
{
    public function __construct(
        private readonly EmprendedorChatbotService $chatbotService,
    ) {}

    public function show(Request $request): JsonResponse
    {
        return response()->json([
            'data' => new EmprendedorChatbotResource($this->chatbotService->show($request->user())),
        ]);
    }

    public function updateSettings(UpdateChatbotSettingsRequest $request): JsonResponse
    {
        $chatbot = $this->chatbotService->updateSettings(
            $request->user(),
            $request->validated(),
            $request->file('avatar_file'),
        );

        return response()->json([
            'message' => 'Chatbot actualizado correctamente.',
            'chatbot' => new EmprendedorChatbotResource($chatbot),
        ]);
    }

    public function updateStatus(UpdateChatbotStatusRequest $request): JsonResponse
    {
        $chatbot = $this->chatbotService->updateStatus($request->user(), $request->validated('status'));

        return response()->json([
            'message' => 'Estado del chatbot actualizado.',
            'chatbot' => new EmprendedorChatbotResource($chatbot),
        ]);
    }

    public function syncNodes(SyncChatbotNodesRequest $request): JsonResponse
    {
        $chatbot = $this->chatbotService->syncNodes($request->user(), $request->validated());

        return response()->json([
            'message' => 'Flujo del chatbot guardado correctamente.',
            'chatbot' => new EmprendedorChatbotResource($chatbot),
        ]);
    }

    public function avatar(Request $request): BinaryFileResponse
    {
        $file = $this->chatbotService->resolveAvatarFile($request->user());

        return response()->file($file['path'], [
            'Content-Type' => $file['mime'],
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }
}
