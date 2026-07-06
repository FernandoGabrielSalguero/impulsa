<?php

namespace App\Http\Controllers\Api\Legacy;

use App\Http\Controllers\Controller;
use App\Models\ApiIntegration;
use App\Services\PublicApi\LegacyPublicProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LegacyProductApiController extends Controller
{
    public function __construct(
        private readonly LegacyPublicProductService $productService,
    ) {}

    public function handle(Request $request): JsonResponse
    {
        /** @var ApiIntegration $integration */
        $integration = $request->attributes->get('api_integration');

        $action = trim((string) $request->input('action', 'list'));

        try {
            return match ($action) {
                'list' => response()->json([
                    'success' => true,
                    'items' => $this->productService->listProducts($integration),
                ]),
                'detail' => response()->json([
                    'success' => true,
                    'data' => $this->productService->findBySlug(
                        $integration,
                        (string) $request->input('slug', ''),
                    ),
                ]),
                default => response()->json([
                    'success' => false,
                    'message' => 'Acción no soportada.',
                ], 422),
            };
        } catch (ValidationException $exception) {
            return response()->json([
                'success' => false,
                'message' => collect($exception->errors())->flatten()->first() ?? 'Solicitud inválida.',
            ], 422);
        }
    }
}
