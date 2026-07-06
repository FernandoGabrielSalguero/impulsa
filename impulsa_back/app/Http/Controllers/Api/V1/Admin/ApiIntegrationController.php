<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreApiIntegrationRequest;
use App\Http\Resources\AdminApiIntegrationCollection;
use App\Http\Resources\AdminApiIntegrationResource;
use App\Models\ApiIntegration;
use App\Services\Admin\ApiIntegrationAdminService;
use App\Support\IntegrationLabels;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiIntegrationController extends Controller
{
    public function __construct(
        private readonly ApiIntegrationAdminService $apiIntegrationAdminService,
    ) {}

    public function index(Request $request): AdminApiIntegrationCollection
    {
        $perPage = max(1, min((int) $request->integer('per_page', 20), 100));
        $result = $this->apiIntegrationAdminService->list(
            $request->query('q'),
            $request->query('status'),
            $perPage,
        );

        return new AdminApiIntegrationCollection($result['data']);
    }

    public function options(): JsonResponse
    {
        $publicApiBaseUrl = rtrim((string) config('impulsa.public_api_base_url'), '/');

        return response()->json([
            'statuses' => collect(IntegrationLabels::statuses())->map(static fn (string $value): array => [
                'value' => $value,
                'label' => IntegrationLabels::statusLabel($value),
            ])->values(),
            'public_api_base_url' => $publicApiBaseUrl,
            'public_api_url' => $publicApiBaseUrl . '/api',
            'endpoints' => [
                ['key' => 'contact', 'label' => 'Formulario de contacto', 'path' => '/api/contact_form_landing_page/index.php'],
                ['key' => 'blog', 'label' => 'Blog', 'path' => '/api/blog_api/index.php'],
                ['key' => 'products', 'label' => 'Productos', 'path' => '/api/producto_api/index.php'],
                ['key' => 'visit_tracker', 'label' => 'Tracker de visitas', 'path' => '/api/visit-tracker.js'],
                ['key' => 'page_visit', 'label' => 'Registro de visita (legacy)', 'path' => '/api/visit_user_page/index.php'],
                ['key' => 'page_visit_v1', 'label' => 'Registro de visita (v1)', 'path' => '/api/v1/public/page-visit'],
                ['key' => 'content_view', 'label' => 'Vista de contenido', 'path' => '/api/v1/public/content-view'],
                ['key' => 'chatbot', 'label' => 'Widget chatbot', 'path' => '/api/chatbot_widget/widget.js'],
                ['key' => 'subscription_status', 'label' => 'Estado de suscripción', 'path' => '/api/v1/public/subscription-status'],
                ['key' => 'subscription_guard', 'label' => 'Guardián de suscripción (JS)', 'path' => '/api/v1/public/subscription-guard.js'],
            ],
        ]);
    }

    public function projectOptions(): JsonResponse
    {
        return response()->json([
            'data' => $this->apiIntegrationAdminService->listProjectOptions(),
        ]);
    }

    public function owners(): JsonResponse
    {
        return response()->json([
            'data' => $this->apiIntegrationAdminService->listOwners(),
        ]);
    }

    public function resolveOwner(Request $request): JsonResponse
    {
        $projectName = trim((string) $request->query('project_name', ''));
        $ownerId = $projectName !== ''
            ? $this->apiIntegrationAdminService->resolveOwnerFromProject($projectName)
            : null;

        return response()->json([
            'user_auth_id' => $ownerId,
        ]);
    }

    public function store(StoreApiIntegrationRequest $request): JsonResponse
    {
        $result = $this->apiIntegrationAdminService->create($request->validated());

        return response()->json([
            'message' => 'Integración creada correctamente.',
            'integration' => new AdminApiIntegrationResource($result['integration']),
            'secret_key' => $result['secret_key'],
            'public_key' => $result['integration']->public_key,
        ], 201);
    }

    public function show(ApiIntegration $apiIntegration): AdminApiIntegrationResource
    {
        return new AdminApiIntegrationResource(
            $this->apiIntegrationAdminService->find((int) $apiIntegration->id),
        );
    }

    public function update(StoreApiIntegrationRequest $request, ApiIntegration $apiIntegration): JsonResponse
    {
        $integration = $this->apiIntegrationAdminService->update($apiIntegration, $request->validated());

        return response()->json([
            'message' => 'Integración actualizada correctamente.',
            'integration' => new AdminApiIntegrationResource($integration),
        ]);
    }

    public function toggleStatus(ApiIntegration $apiIntegration): JsonResponse
    {
        $integration = $this->apiIntegrationAdminService->toggleStatus($apiIntegration);

        return response()->json([
            'message' => $integration->status === 'active'
                ? 'Integración activada.'
                : 'Integración desactivada.',
            'integration' => new AdminApiIntegrationResource($integration),
        ]);
    }

    public function regeneratePublicKey(ApiIntegration $apiIntegration): JsonResponse
    {
        $result = $this->apiIntegrationAdminService->regeneratePublicKey($apiIntegration);

        return response()->json([
            'message' => 'Clave pública regenerada correctamente.',
            'integration' => new AdminApiIntegrationResource($result['integration']),
            'public_key' => $result['public_key'],
        ]);
    }

    public function regenerateSecretKey(ApiIntegration $apiIntegration): JsonResponse
    {
        $result = $this->apiIntegrationAdminService->regenerateSecretKey($apiIntegration);

        return response()->json([
            'message' => 'Clave secreta regenerada correctamente.',
            'integration' => new AdminApiIntegrationResource($result['integration']),
            'secret_key' => $result['secret_key'],
        ]);
    }
}
