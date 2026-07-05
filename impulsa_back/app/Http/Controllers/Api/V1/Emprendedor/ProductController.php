<?php

namespace App\Http\Controllers\Api\V1\Emprendedor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreApiProductRequest;
use App\Http\Requests\Admin\UpdateApiProductRequest;
use App\Http\Requests\Admin\UpdateApiProductStatusRequest;
use App\Http\Resources\AdminApiProductResource;
use App\Http\Resources\EmprendedorApiProductResource;
use App\Models\ApiProduct;
use App\Services\Emprendedor\EmprendedorProductService;
use App\Support\ApiProductLabels;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductController extends Controller
{
    public function __construct(
        private readonly EmprendedorProductService $productService,
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

    public function summary(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->productService->summary($request->user()),
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'data' => EmprendedorApiProductResource::collection(
                $this->productService->list(
                    $request->user(),
                    $request->query('q'),
                    $request->query('status'),
                ),
            ),
        ]);
    }

    public function show(Request $request, ApiProduct $apiProduct): JsonResponse
    {
        return response()->json([
            'data' => new EmprendedorApiProductResource($this->productService->find($request->user(), (int) $apiProduct->id)),
        ]);
    }

    public function store(StoreApiProductRequest $request): JsonResponse
    {
        $product = $this->productService->create(
            $request->user(),
            $request->validated(),
            $request->allFiles(),
        );

        return response()->json([
            'message' => 'Producto creado correctamente.',
            'product' => new EmprendedorApiProductResource($this->productService->find($request->user(), (int) $product->id)),
        ], 201);
    }

    public function update(UpdateApiProductRequest $request, ApiProduct $apiProduct): JsonResponse
    {
        $product = $this->productService->update(
            $request->user(),
            $apiProduct,
            $request->validated(),
            $request->allFiles(),
        );

        return response()->json([
            'message' => 'Producto actualizado correctamente.',
            'product' => new EmprendedorApiProductResource($this->productService->find($request->user(), (int) $product->id)),
        ]);
    }

    public function updateStatus(UpdateApiProductStatusRequest $request, ApiProduct $apiProduct): JsonResponse
    {
        $product = $this->productService->updateStatus(
            $request->user(),
            $apiProduct,
            $request->validated('status'),
        );

        return response()->json([
            'message' => 'Estado actualizado correctamente.',
            'product' => new EmprendedorApiProductResource($this->productService->find($request->user(), (int) $product->id)),
        ]);
    }

    public function media(Request $request, ApiProduct $apiProduct, string $mediaType): BinaryFileResponse
    {
        $this->productService->find($request->user(), (int) $apiProduct->id);
        $media = $this->productService->resolveMediaFile($request->user(), $apiProduct, $mediaType);

        return response()->file($media['path'], [
            'Content-Type' => $media['mime'],
        ]);
    }
}
