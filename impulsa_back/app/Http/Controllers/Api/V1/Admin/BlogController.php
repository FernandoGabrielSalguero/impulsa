<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminBlogAdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function __construct(
        private readonly AdminBlogAdminService $blogAdminService,
    ) {}

    public function summary(): JsonResponse
    {
        return response()->json([
            'data' => $this->blogAdminService->summary(),
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->blogAdminService->list(
            $request->query('q'),
            $request->query('status'),
            (int) $request->integer('per_page', 20),
            (int) $request->integer('page', 1),
        );

        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function show(int $postId): JsonResponse
    {
        return response()->json([
            'data' => $this->blogAdminService->find($postId),
        ]);
    }
}
