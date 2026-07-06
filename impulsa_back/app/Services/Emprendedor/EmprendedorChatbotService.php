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
            $chatbot = Chatbot::query()->create([
                'api_integration_id' => $integration->id,
                'name' => 'Chatbot ' . $integration->project_name,
                'avatar_url' => null,
                'whatsapp' => $whatsapp,
                'initial_message' => 'Hola, soy el asistente del sitio. Elegi una opcion para continuar.',
                'status' => 'inactive',
                'disabled_by_admin' => false,
            ]);

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

        $chatbot->fill([
            'name' => trim((string) $data['name']),
            'avatar_url' => $avatarUrl,
            'whatsapp' => trim((string) $data['whatsapp']),
            'initial_message' => trim((string) $data['initial_message']),
        ]);
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
            $existingNodeIds = $chatbot->nodes()->pluck('id');
            ChatbotNodeOption::query()->whereIn('node_id', $existingNodeIds)->delete();
            $chatbot->nodes()->delete();

            $nodeIdMap = [];

            foreach ($nodes as $index => $nodeData) {
                $clientKey = trim((string) ($nodeData['client_key'] ?? 'node_' . $index));
                $node = ChatbotNode::query()->create([
                    'chatbot_id' => $chatbot->id,
                    'title' => trim((string) ($nodeData['title'] ?? 'Nodo ' . ($index + 1))),
                    'body' => trim((string) ($nodeData['body'] ?? '')),
                    'sort_order' => (int) ($nodeData['sort_order'] ?? $index + 1),
                    'is_start' => (bool) ($nodeData['is_start'] ?? $index === 0),
                    'status' => in_array($nodeData['status'] ?? 'active', ['active', 'inactive'], true)
                        ? $nodeData['status']
                        : 'active',
                ]);
                $nodeIdMap[$clientKey] = (int) $node->id;
            }

            foreach ($nodes as $index => $nodeData) {
                $clientKey = trim((string) ($nodeData['client_key'] ?? 'node_' . $index));
                $nodeId = $nodeIdMap[$clientKey] ?? null;

                if ($nodeId === null) {
                    continue;
                }

                foreach ($nodeData['options'] ?? [] as $optionIndex => $optionData) {
                    $targetKey = trim((string) ($optionData['target_client_key'] ?? ''));
                    $targetNodeId = $targetKey !== '' && isset($nodeIdMap[$targetKey])
                        ? $nodeIdMap[$targetKey]
                        : null;

                    ChatbotNodeOption::query()->create([
                        'node_id' => $nodeId,
                        'label' => trim((string) ($optionData['label'] ?? 'Opción')),
                        'action_type' => in_array($optionData['action_type'] ?? 'go_to_node', ['go_to_node', 'whatsapp', 'restart', 'close'], true)
                            ? $optionData['action_type']
                            : 'go_to_node',
                        'target_node_id' => $targetNodeId,
                        'sort_order' => (int) ($optionData['sort_order'] ?? $optionIndex + 1),
                    ]);
                }
            }

            return $this->reload($chatbot);
        });
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
