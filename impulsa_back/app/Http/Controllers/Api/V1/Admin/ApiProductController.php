<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreApiProductRequest;
use App\Http\Requests\Admin\UpdateApiProductRequest;
use App\Http\Requests\Admin\UpdateApiProductStatusRequest;
use App\Http\Resources\AdminApiProductIntegrationOptionResource;
use App\Http\Resources\AdminApiProductResource;
use App\Models\ApiProduct;
use App\Services\Admin\ApiProductAdminService;
use App\Support\ApiProductLabels;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ApiProductController extends Controller
{
    public function __construct(
        private readonly ApiProductAdminService $apiProductAdminService,
    ) {}

    public function options(): JsonResponse
    {
        return response()->json([
            'statuses' => collect(ApiProductLabels::statuses())->map(static fn (string $value): array => [
                'value' => $value,
                'label' => ApiProductLabels::statusLabel($value),
            ])->values(),
            'availabilities' => collect(ApiProductLabels::availabilities())->map(static fn (string $value): array => [
                'value' => $value,
                'label' => ApiProductLabels::availabilityLabel($value),
            ])->values(),
        ]);
    }

    public function integrationOptions(): JsonResponse
    {
        return response()->json([
            'data' => AdminApiProductIntegrationOptionResource::collection(
                $this->apiProductAdminService->integrationOptions(),
            )->resolve(),
        ]);
    }

    public function summary(Request $request): JsonResponse
    {
        $integrationId = $request->filled('integration_id')
            ? (int) $request->integer('integration_id')
            : null;

        if ($integrationId !== null) {
            $this->apiProductAdminService->findIntegration($integrationId);
        }

        return response()->json([
            'data' => $this->apiProductAdminService->summary($integrationId),
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $integrationId = (int) $request->integer('integration_id');

        if ($integrationId <= 0) {
            return response()->json([
                'message' => 'Debés seleccionar una integración API.',
                'errors' => [
                    'integration_id' => ['Debés seleccionar una integración API.'],
                ],
            ], 422);
        }

        $products = $this->apiProductAdminService->listByIntegration(
            $integrationId,
            $request->query('q'),
            $request->query('status'),
        );

        return response()->json([
            'data' => AdminApiProductResource::collection($products)->resolve(),
            'summary' => $this->apiProductAdminService->summary($integrationId),
        ]);
    }

    public function store(StoreApiProductRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $integrationId = (int) $validated['api_integration_id'];
        unset($validated['api_integration_id']);

        $product = $this->apiProductAdminService->create(
            $integrationId,
            $validated,
            [
                'main_image_file' => $request->file('main_image_file'),
                'thumbnail_file' => $request->file('thumbnail_file'),
                'attachment_file' => $request->file('attachment_file'),
            ],
        );

        return response()->json([
            'message' => 'Producto creado correctamente.',
            'product' => new AdminApiProductResource($this->apiProductAdminService->find((int) $product->id)),
        ], 201);
    }

    public function show(ApiProduct $apiProduct): AdminApiProductResource
    {
        return new AdminApiProductResource($this->apiProductAdminService->find((int) $apiProduct->id));
    }

    public function update(UpdateApiProductRequest $request, ApiProduct $apiProduct): JsonResponse
    {
        $validated = $request->validated();
        unset($validated['api_integration_id']);

        $updated = $this->apiProductAdminService->update(
            $apiProduct,
            $validated,
            [
                'main_image_file' => $request->file('main_image_file'),
                'thumbnail_file' => $request->file('thumbnail_file'),
                'attachment_file' => $request->file('attachment_file'),
            ],
        );

        return response()->json([
            'message' => 'Producto actualizado correctamente.',
            'product' => new AdminApiProductResource($this->apiProductAdminService->find((int) $updated->id)),
        ]);
    }

    public function updateStatus(UpdateApiProductStatusRequest $request, ApiProduct $apiProduct): JsonResponse
    {
        $updated = $this->apiProductAdminService->updateStatus($apiProduct, (string) $request->validated('status'));

        return response()->json([
            'message' => 'Estado actualizado correctamente.',
            'product' => new AdminApiProductResource($this->apiProductAdminService->find((int) $updated->id)),
        ]);
    }

    public function taxonomy(Request $request): JsonResponse
    {
        $integrationId = (int) $request->integer('integration_id');

        if ($integrationId <= 0) {
            return response()->json([
                'message' => 'Debés seleccionar una integración API.',
                'errors' => [
                    'integration_id' => ['Debés seleccionar una integración API.'],
                ],
            ], 422);
        }

        return response()->json([
            'data' => $this->apiProductAdminService->taxonomy($integrationId),
        ]);
    }

    public function destroy(ApiProduct $apiProduct): JsonResponse
    {
        $this->apiProductAdminService->delete($apiProduct);

        return response()->json([
            'message' => 'Producto eliminado correctamente.',
        ]);
    }

    public function media(ApiProduct $apiProduct, string $mediaType): BinaryFileResponse
    {
        $file = $this->apiProductAdminService->resolveMediaFile($apiProduct, $mediaType);

        return response()->file($file['absolute_path'], [
            'Content-Type' => $file['mime_type'],
            'Content-Disposition' => 'inline; filename="' . $file['download_name'] . '"',
        ]);
    }
}
