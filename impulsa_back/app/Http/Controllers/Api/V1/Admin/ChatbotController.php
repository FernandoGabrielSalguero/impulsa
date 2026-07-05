<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAdminChatbotBlockRequest;
use App\Http\Resources\AdminChatbotCollection;
use App\Http\Resources\AdminChatbotResource;
use App\Models\Chatbot;
use App\Services\Admin\ChatbotAdminService;
use App\Support\ChatbotLabels;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function __construct(
        private readonly ChatbotAdminService $chatbotAdminService,
    ) {}

    public function summary(): JsonResponse
    {
        return response()->json([
            'data' => $this->chatbotAdminService->summary(),
        ]);
    }

    public function options(): JsonResponse
    {
        return response()->json([
            'statuses' => collect(ChatbotLabels::statuses())->map(static fn (string $value): array => [
                'value' => $value,
                'label' => ChatbotLabels::statusLabel($value),
            ])->values(),
            'admin_lock_filters' => [
                ['value' => '__all__', 'label' => 'Todos'],
                ['value' => 'free', 'label' => 'Libre'],
                ['value' => 'blocked', 'label' => 'Bloqueado'],
            ],
        ]);
    }

    public function index(Request $request): AdminChatbotCollection
    {
        $perPage = max(1, min((int) $request->integer('per_page', 20), 100));
        $result = $this->chatbotAdminService->list(
            $request->query('q'),
            $request->query('status'),
            $request->query('blocked'),
            $perPage,
        );

        return new AdminChatbotCollection(
            $result['data'],
            $this->chatbotAdminService->summary(),
        );
    }

    public function show(Chatbot $chatbot): AdminChatbotResource
    {
        return new AdminChatbotResource($this->chatbotAdminService->find((int) $chatbot->id));
    }

    public function updateBlock(UpdateAdminChatbotBlockRequest $request, Chatbot $chatbot): JsonResponse
    {
        $blocked = (bool) $request->boolean('blocked');
        $this->chatbotAdminService->setAdminBlock($chatbot, $blocked);

        return response()->json([
            'message' => $blocked
                ? 'Chatbot desactivado por administración.'
                : 'Chatbot rehabilitado por administración.',
            'chatbot' => new AdminChatbotResource($this->chatbotAdminService->find((int) $chatbot->id)),
        ]);
    }
}
