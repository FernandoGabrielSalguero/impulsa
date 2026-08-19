<?php

namespace App\Services\Emprendedor;

use App\Models\ApiIntegration;
use App\Models\Chatbot;
use App\Models\ChatbotNode;
use App\Models\ChatbotNodeOption;
use App\Models\UserAuth;
use App\Services\Chatbot\ChatbotAvatarStorageService;
use App\Support\EmprendedorIntegrationAccess;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class EmprendedorChatbotService
{
    public function __construct(
        private readonly EmprendedorIntegrationAccess $integrationAccess,
        private readonly ChatbotAvatarStorageService $avatarStorage,
    ) {}

    public function show(UserAuth $user): Chatbot
    {
        $integration = $this->integrationAccess->requireIntegration($user);

        $chatbot = Chatbot::query()
            ->with(['nodes.options'])
            ->where('api_integration_id', $integration->id)
            ->first();

        if ($chatbot === null) {
            $chatbot = $this->createDefaultChatbot($user, $integration);
        } elseif (! $chatbot->nodes()->exists()) {
            $chatbot = $this->seedDefaultNodes($chatbot);
        }

        return $chatbot;
    }

    private function createDefaultChatbot(UserAuth $user, ApiIntegration $integration): Chatbot
    {
        $whatsapp = trim((string) DB::table('user_contacto')
            ->where('user_auth_id', $user->id)
            ->value('whatsapp'));

        return DB::transaction(function () use ($integration, $whatsapp): Chatbot {
            $payload = [
                'api_integration_id' => $integration->id,
                'name' => 'Chatbot ' . $integration->project_name,
                'avatar_url' => null,
                'whatsapp' => $whatsapp,
                'initial_message' => 'Hola, soy el asistente del sitio. Elegi una opcion para continuar.',
                'status' => 'inactive',
                'disabled_by_admin' => false,
            ];

            if (Schema::hasColumn('chatbots', 'icon_background_color')) {
                $payload['icon_background_color'] = Chatbot::DEFAULT_ICON_BACKGROUND_COLOR;
            }

            $chatbot = Chatbot::query()->create($payload);

            return $this->seedDefaultNodes($chatbot);
        });
    }

    private function seedDefaultNodes(Chatbot $chatbot): Chatbot
    {
        if ($chatbot->nodes()->exists()) {
            return $this->reload($chatbot);
        }

        return DB::transaction(function () use ($chatbot): Chatbot {
            $startNode = ChatbotNode::query()->create([
                'chatbot_id' => $chatbot->id,
                'title' => 'Inicio',
                'body' => 'Bienvenido. Elegi una opcion para continuar.',
                'sort_order' => 1,
                'is_start' => true,
                'status' => 'active',
            ]);

            ChatbotNodeOption::query()->create([
                'node_id' => $startNode->id,
                'label' => 'Hablar por WhatsApp',
                'action_type' => 'whatsapp',
                'target_node_id' => null,
                'sort_order' => 1,
            ]);

            return $this->reload($chatbot);
        });
    }

    /** @param array<string, mixed> $data */
    public function updateSettings(UserAuth $user, array $data, ?UploadedFile $avatarFile = null): Chatbot
    {
        $chatbot = $this->show($user);
        $this->assertEditable($chatbot);

        $avatarUrl = trim((string) ($data['avatar_url'] ?? '')) ?: null;

        if ($avatarFile instanceof UploadedFile) {
            $avatarUrl = $this->avatarStorage->store($chatbot, $avatarFile);
        } elseif (filter_var($data['remove_avatar'] ?? false, FILTER_VALIDATE_BOOL)) {
            $this->avatarStorage->deleteStoredPath($chatbot->avatar_url);
            $avatarUrl = null;
        } elseif ($avatarUrl === null) {
            $avatarUrl = $chatbot->avatar_url;
        }

        $payload = [
            'name' => trim((string) $data['name']),
            'avatar_url' => $avatarUrl,
            'whatsapp' => trim((string) $data['whatsapp']),
            'initial_message' => trim((string) $data['initial_message']),
        ];

        if (Schema::hasColumn('chatbots', 'icon_background_color')) {
            $payload['icon_background_color'] = $this->normalizeIconBackgroundColor(
                $data['icon_background_color'] ?? null,
                $chatbot->icon_background_color,
            );
        }

        $chatbot->fill($payload);
        $chatbot->save();

        return $this->reload($chatbot);
    }

    public function resolveAvatarFile(UserAuth $user): array
    {
        $chatbot = $this->show($user);
        $absolutePath = $this->avatarStorage->resolveAbsolutePath($chatbot->avatar_url);

        if ($absolutePath === null) {
            throw ValidationException::withMessages([
                'avatar' => ['No hay avatar cargado para este chatbot.'],
            ]);
        }

        return [
            'path' => $absolutePath,
            'mime' => mime_content_type($absolutePath) ?: 'application/octet-stream',
        ];
    }

    public function updateStatus(UserAuth $user, string $status): Chatbot
    {
        $chatbot = $this->show($user);
        $this->assertEditable($chatbot);

        if (! in_array($status, ['active', 'inactive'], true)) {
            throw ValidationException::withMessages([
                'status' => ['Estado inválido.'],
            ]);
        }

        if ($status === 'active' && ! $chatbot->nodes()->where('status', 'active')->exists()) {
            $chatbot = $this->seedDefaultNodes($chatbot);
        }

        $chatbot->status = $status;
        $chatbot->save();

        return $this->reload($chatbot);
    }

    /** @param array<string, mixed> $data */
    public function syncNodes(UserAuth $user, array $data): Chatbot
    {
        $chatbot = $this->show($user);
        $this->assertEditable($chatbot);

        $nodes = is_array($data['nodes'] ?? null) ? $data['nodes'] : [];

        return DB::transaction(function () use ($chatbot, $nodes): Chatbot {
            $existingNodes = $chatbot->nodes()->with('options')->get()->keyBy('id');
            $keptNodeIds = [];
            $nodeIdMap = [];

            foreach ($nodes as $index => $nodeData) {
                $clientKey = trim((string) ($nodeData['client_key'] ?? 'node_' . $index));
                $incomingId = (int) ($nodeData['id'] ?? 0);
                $attributes = [
                    'title' => trim((string) ($nodeData['title'] ?? 'Nodo ' . ($index + 1))),
                    'body' => trim((string) ($nodeData['body'] ?? '')),
                    'sort_order' => (int) ($nodeData['sort_order'] ?? $index + 1),
                    'is_start' => (bool) ($nodeData['is_start'] ?? $index === 0),
                    'status' => in_array($nodeData['status'] ?? 'active', ['active', 'inactive'], true)
                        ? (string) $nodeData['status']
                        : 'active',
                ];

                if ($incomingId > 0 && $existingNodes->has($incomingId)) {
                    $node = $existingNodes->get($incomingId);
                    $node->fill($attributes);
                    $node->save();
                } else {
                    $node = ChatbotNode::query()->create([
                        'chatbot_id' => $chatbot->id,
                        ...$attributes,
                    ]);
                }

                $keptNodeIds[] = (int) $node->id;
                $nodeIdMap[$clientKey] = (int) $node->id;
            }

            $chatbot->nodes()->whereNotIn('id', $keptNodeIds !== [] ? $keptNodeIds : [0])->delete();

            foreach ($nodes as $index => $nodeData) {
                $clientKey = trim((string) ($nodeData['client_key'] ?? 'node_' . $index));
                $nodeId = $nodeIdMap[$clientKey] ?? null;

                if ($nodeId === null) {
                    continue;
                }

                $existingOptions = ChatbotNodeOption::query()
                    ->where('node_id', $nodeId)
                    ->get()
                    ->keyBy('id');
                $keptOptionIds = [];

                foreach ($nodeData['options'] ?? [] as $optionIndex => $optionData) {
                    $targetKey = trim((string) ($optionData['target_client_key'] ?? ''));
                    $targetNodeId = $targetKey !== '' && isset($nodeIdMap[$targetKey])
                        ? $nodeIdMap[$targetKey]
                        : null;
                    $optionAttributes = [
                        'label' => trim((string) ($optionData['label'] ?? 'Opción')),
                        'action_type' => in_array($optionData['action_type'] ?? 'go_to_node', ['go_to_node', 'whatsapp', 'restart', 'close'], true)
                            ? (string) $optionData['action_type']
                            : 'go_to_node',
                        'target_node_id' => $targetNodeId,
                        'sort_order' => (int) ($optionData['sort_order'] ?? $optionIndex + 1),
                    ];
                    $incomingOptionId = (int) ($optionData['id'] ?? 0);

                    if ($incomingOptionId > 0 && $existingOptions->has($incomingOptionId)) {
                        $option = $existingOptions->get($incomingOptionId);
                        $option->fill($optionAttributes);
                        $option->save();
                    } else {
                        $option = ChatbotNodeOption::query()->create([
                            'node_id' => $nodeId,
                            ...$optionAttributes,
                        ]);
                    }

                    $keptOptionIds[] = (int) $option->id;
                }

                ChatbotNodeOption::query()
                    ->where('node_id', $nodeId)
                    ->whereNotIn('id', $keptOptionIds !== [] ? $keptOptionIds : [0])
                    ->delete();
            }

            return $this->reload($chatbot);
        });
    }

    private function normalizeIconBackgroundColor(mixed $value, mixed $fallback = null): string
    {
        $candidate = strtoupper(trim((string) $value));

        if (preg_match('/^#[0-9A-F]{6}$/', $candidate) === 1) {
            return $candidate;
        }

        $fallbackColor = strtoupper(trim((string) $fallback));

        return preg_match('/^#[0-9A-F]{6}$/', $fallbackColor) === 1
            ? $fallbackColor
            : Chatbot::DEFAULT_ICON_BACKGROUND_COLOR;
    }

    private function assertEditable(Chatbot $chatbot): void
    {
        if ($chatbot->disabled_by_admin) {
            throw ValidationException::withMessages([
                'chatbot' => ['El chatbot fue bloqueado por un administrador.'],
            ]);
        }
    }

    private function reload(Chatbot $chatbot): Chatbot
    {
        return Chatbot::query()
            ->with(['nodes.options'])
            ->findOrFail($chatbot->id);
    }
}
