<?php

namespace App\Http\Resources;

use App\Models\Chatbot;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmprendedorChatbotResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Chatbot $chatbot */
        $chatbot = $this->resource;

        return [
            'id' => (int) $chatbot->id,
            'name' => (string) $chatbot->name,
            'avatar_url' => $this->resolveAvatarUrl($chatbot, $request),
            'whatsapp' => (string) $chatbot->whatsapp,
            'initial_message' => (string) $chatbot->initial_message,
            'status' => (string) $chatbot->status,
            'disabled_by_admin' => (bool) $chatbot->disabled_by_admin,
            'nodes' => $chatbot->relationLoaded('nodes')
                ? $chatbot->nodes->map(static fn ($node): array => [
                    'id' => (int) $node->id,
                    'title' => (string) $node->title,
                    'body' => (string) $node->body,
                    'sort_order' => (int) $node->sort_order,
                    'is_start' => (bool) $node->is_start,
                    'status' => (string) $node->status,
                    'options' => $node->relationLoaded('options')
                        ? $node->options->map(static fn ($option): array => [
                            'id' => (int) $option->id,
                            'label' => (string) $option->label,
                            'action_type' => (string) $option->action_type,
                            'target_node_id' => $option->target_node_id ? (int) $option->target_node_id : null,
                            'sort_order' => (int) $option->sort_order,
                        ])->values()->all()
                        : [],
                ])->values()->all()
                : [],
        ];
    }

    private function resolveAvatarUrl(Chatbot $chatbot, Request $request): ?string
    {
        $stored = trim((string) ($chatbot->avatar_url ?? ''));

        if ($stored === '') {
            return null;
        }

        if (str_starts_with($stored, 'http://') || str_starts_with($stored, 'https://')) {
            return $stored;
        }

        if (str_starts_with($stored, '/')) {
            return rtrim((string) config('app.url'), '/') . $stored;
        }

        if (str_starts_with($stored, 'chatbot-avatars/')) {
            $version = $chatbot->updated_at?->getTimestamp() ?? time();

            return url('/api/v1/emprendedor/chatbot/avatar') . '?v=' . $version;
        }

        return $stored;
    }
}
