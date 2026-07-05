<?php

namespace App\Http\Controllers\Api\V1\Emprendedor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Emprendedor\StoreBlogPostRequest;
use App\Http\Requests\Emprendedor\UpdateBlogPostRequest;
use App\Http\Requests\Emprendedor\UpdateBlogPostStatusRequest;
use App\Services\Emprendedor\EmprendedorBlogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BlogController extends Controller
{
    public function __construct(
        private readonly EmprendedorBlogService $blogService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->blogService->list(
                $request->user(),
                $request->query('q'),
                $request->query('status'),
            ),
        ]);
    }

    public function taxonomy(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->blogService->taxonomy($request->user()),
        ]);
    }

    public function show(Request $request, int $postId): JsonResponse
    {
        return response()->json([
            'data' => $this->blogService->find($request->user(), $postId),
        ]);
    }

    public function store(StoreBlogPostRequest $request): JsonResponse
    {
        $post = $this->blogService->create(
            $request->user(),
            $request->validated(),
            $request->allFiles(),
        );

        return response()->json([
            'message' => 'Artículo creado correctamente.',
            'post' => $post,
        ], 201);
    }

    public function update(UpdateBlogPostRequest $request, int $postId): JsonResponse
    {
        $post = $this->blogService->update(
            $request->user(),
            $postId,
            $request->validated(),
            $request->allFiles(),
        );

        return response()->json([
            'message' => 'Artículo actualizado correctamente.',
            'post' => $post,
        ]);
    }

    public function updateStatus(UpdateBlogPostStatusRequest $request, int $postId): JsonResponse
    {
        $post = $this->blogService->updateStatus($request->user(), $postId, $request->validated('status'));

        return response()->json([
            'message' => 'Estado del artículo actualizado.',
            'post' => $post,
        ]);
    }

    public function media(Request $request, int $postId, string $mediaType): BinaryFileResponse
    {
        $media = $this->blogService->resolveMediaFile($request->user(), $postId, $mediaType);

        return response()->file($media['path'], [
            'Content-Type' => $media['mime'],
        ]);
    }
}
