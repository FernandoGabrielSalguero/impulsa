<?php

namespace App\PublicEmbed\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ApiIntegration;
use App\PublicEmbed\Services\PublicBlogService;
use App\PublicEmbed\Support\PublicResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PublicBlogController extends Controller
{
    public function __construct(
        private readonly PublicBlogService $blogService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        /** @var ApiIntegration $integration */
        $integration = $request->attributes->get('api_integration');

        $items = $this->blogService->listPosts($integration);

        return PublicResponse::success($items, [
            'feature' => 'blog',
            'count' => count($items),
        ]);
    }

    public function show(Request $request, string $slug): JsonResponse
    {
        /** @var ApiIntegration $integration */
        $integration = $request->attributes->get('api_integration');

        try {
            $post = $this->blogService->findBySlug($integration, $slug);
        } catch (ValidationException $exception) {
            return PublicResponse::error(
                collect($exception->errors())->flatten()->first() ?? 'Artículo no encontrado.',
                'not_found',
                404,
            );
        }

        return PublicResponse::success($post, ['feature' => 'blog']);
    }
}
