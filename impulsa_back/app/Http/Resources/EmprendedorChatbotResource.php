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
            'has_avatar' => $this->hasManagedAvatar($chatbot),
            'icon_background_color' => $this->resolveIconBackgroundColor($chatbot),
            'whatsapp' => (string) $chatbot->whatsapp,
            'initial_message' => (string) $chatbot->initial_message,
            'status' => (string) $chatbot->status,
            'disabled_by_admin' => (bool) $chatbot->disabled_by_admin,
            'updated_at' => $chatbot->updated_at?->toISOString(),
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

        if ($this->hasManagedAvatar($chatbot)) {
            return null;
        }

        return $stored;
    }

    private function hasManagedAvatar(Chatbot $chatbot): bool
    {
        $stored = trim((string) ($chatbot->avatar_url ?? ''));

        return $stored !== '' && str_starts_with($stored, 'chatbot-avatars/');
    }

    private function resolveIconBackgroundColor(Chatbot $chatbot): string
    {
        $value = strtoupper(trim((string) ($chatbot->icon_background_color ?? '')));

        return preg_match('/^#[0-9A-F]{6}$/', $value) === 1
            ? $value
            : Chatbot::DEFAULT_ICON_BACKGROUND_COLOR;
    }
}
