<?php

namespace App\PublicEmbed\Services;

use App\Models\ApiIntegration;
use App\Models\Chatbot;
use App\Models\ChatbotEvent;
use App\Services\Chatbot\ChatbotAvatarStorageService;
use App\Services\PublicApi\PublicMediaUrlBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PublicChatbotService
{
    private const ALLOWED_EVENT_TYPES = [
        'widget_loaded',
        'bubble_opened',
        'question_viewed',
        'option_clicked',
        'whatsapp_clicked',
        'chat_closed',
    ];

    public function __construct(
        private readonly ChatbotAvatarStorageService $avatarStorage,
        private readonly PublicMediaUrlBuilder $mediaUrlBuilder,
    ) {}

    /** @return array<string, mixed>|null */
    public function publicConfig(ApiIntegration $integration): ?array
    {
        $chatbot = Chatbot::query()
            ->with(['nodes' => fn ($q) => $q->where('status', 'active')->orderBy('sort_order'), 'nodes.options' => fn ($q) => $q->orderBy('sort_order')])
            ->where('api_integration_id', $integration->id)
            ->first();

        if ($chatbot === null) {
            return null;
        }

        if ($chatbot->status !== 'active' || $chatbot->disabled_by_admin || $integration->status !== 'active') {
            return null;
        }

        $avatarUrl = $this->resolvePublicAvatarUrl($integration, $chatbot);

        return [
            'id' => (int) $chatbot->id,
            'name' => (string) $chatbot->name,
            'avatar_url' => $avatarUrl,
            'whatsapp' => (string) $chatbot->whatsapp,
            'initial_message' => (string) $chatbot->initial_message,
            'nodes' => $chatbot->nodes->map(fn ($node): array => [
                'id' => (int) $node->id,
                'title' => (string) $node->title,
                'body' => (string) $node->body,
                'is_start' => (bool) $node->is_start,
                'options' => $node->options->map(fn ($option): array => [
                    'id' => (int) $option->id,
                    'label' => (string) $option->label,
                    'action_type' => (string) $option->action_type,
                    'target_node_id' => $option->target_node_id !== null ? (int) $option->target_node_id : null,
                ])->values()->all(),
            ])->values()->all(),
        ];
    }

    public function resolveAvatarFile(ApiIntegration $integration): array
    {
        $chatbot = Chatbot::query()
            ->where('api_integration_id', $integration->id)
            ->first();

        if ($chatbot === null || $chatbot->avatar_url === null) {
            throw ValidationException::withMessages([
                'avatar' => ['Avatar no disponible.'],
            ]);
        }

        $absolutePath = $this->avatarStorage->resolveAbsolutePath($chatbot->avatar_url);

        if ($absolutePath === null) {
            throw ValidationException::withMessages([
                'avatar' => ['Avatar no encontrado.'],
            ]);
        }

        return [
            'path' => $absolutePath,
            'mime' => mime_content_type($absolutePath) ?: 'application/octet-stream',
        ];
    }

    /** @param array<string, mixed> $data */
    public function recordEvent(ApiIntegration $integration, array $data, Request $request): void
    {
        $chatbot = Chatbot::query()
            ->where('api_integration_id', $integration->id)
            ->first();

        if ($chatbot === null) {
            throw ValidationException::withMessages([
                'chatbot' => ['Chatbot no configurado.'],
            ]);
        }

        $eventType = (string) ($data['event_type'] ?? '');

        if (! in_array($eventType, self::ALLOWED_EVENT_TYPES, true)) {
            throw ValidationException::withMessages([
                'event_type' => ['Tipo de evento inválido.'],
            ]);
        }

        ChatbotEvent::query()->create([
            'chatbot_id' => $chatbot->id,
            'api_integration_id' => $integration->id,
            'event_type' => $eventType,
            'node_id' => isset($data['node_id']) ? (int) $data['node_id'] : null,
            'option_id' => isset($data['option_id']) ? (int) $data['option_id'] : null,
            'page_url' => isset($data['page_url']) ? mb_substr((string) $data['page_url'], 0, 500) : null,
            'metadata_json' => isset($data['metadata']) ? json_encode($data['metadata'], JSON_UNESCAPED_UNICODE) : null,
            'ip_hash' => $this->hashIp($request->ip()),
            'user_agent' => mb_substr((string) $request->userAgent(), 0, 255),
            'created_at' => now(),
        ]);
    }

    public function nodesCount(ApiIntegration $integration): int
    {
        return (int) DB::table('chatbot_nodes as n')
            ->join('chatbots as c', 'c.id', '=', 'n.chatbot_id')
            ->where('c.api_integration_id', $integration->id)
            ->where('n.status', 'active')
            ->count();
    }

    private function resolvePublicAvatarUrl(ApiIntegration $integration, Chatbot $chatbot): ?string
    {
        $stored = trim((string) $chatbot->avatar_url);

        if ($stored === '') {
            return null;
        }

        if ($this->avatarStorage->isManagedPath($stored)) {
            return $this->mediaUrlBuilder->publicApiUrl(
                '/v1/public/chatbot/avatar?public_key=' . urlencode((string) $integration->public_key)
            );
        }

        return $this->mediaUrlBuilder->url($stored);
    }

    private function hashIp(?string $ipAddress): ?string
    {
        $ipAddress = trim((string) $ipAddress);

        return $ipAddress === '' ? null : hash('sha256', $ipAddress);
    }
}
