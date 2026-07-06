<?php

namespace App\PublicEmbed\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ApiIntegration;
use App\PublicEmbed\Services\PublicProductService;
use App\PublicEmbed\Support\PublicResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PublicProductController extends Controller
{
    public function __construct(
        private readonly PublicProductService $productService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        /** @var ApiIntegration $integration */
        $integration = $request->attributes->get('api_integration');

        $items = $this->productService->listProducts($integration);

        return PublicResponse::success($items, [
            'feature' => 'products',
            'count' => count($items),
        ]);
    }

    public function show(Request $request, string $slug): JsonResponse
    {
        /** @var ApiIntegration $integration */
        $integration = $request->attributes->get('api_integration');

        try {
            $product = $this->productService->findBySlug($integration, $slug);
        } catch (ValidationException $exception) {
            return PublicResponse::error(
                collect($exception->errors())->flatten()->first() ?? 'Producto no encontrado.',
                'not_found',
                404,
            );
        }

        return PublicResponse::success($product, ['feature' => 'products']);
    }
}
