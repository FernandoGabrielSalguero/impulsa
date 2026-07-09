<?php

namespace App\Http\Controllers\Api\V1\Emprendedor;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminAcademiaVideoResource;
use App\Services\Emprendedor\EmprendedorAcademiaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AcademiaController extends Controller
{
    public function __construct(
        private readonly EmprendedorAcademiaService $academiaService,
    ) {}

    public function taxonomy(): JsonResponse
    {
        return response()->json([
            'data' => $this->academiaService->taxonomy(),
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->academiaService->list(
            $request->query('q'),
            $request->query('category'),
            $request->query('subcategory'),
            max(1, min((int) $request->integer('per_page', 20), 100)),
            max(1, (int) $request->integer('page', 1)),
        );

        return response()->json([
            'data' => AdminAcademiaVideoResource::collection($paginator->items())->resolve(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function show(int $videoId): JsonResponse
    {
        return response()->json([
            'data' => new AdminAcademiaVideoResource($this->academiaService->find($videoId)),
        ]);
    }

    public function downloadAttachment(int $videoId, int $attachmentId): BinaryFileResponse
    {
        $absolutePath = $this->academiaService->resolveAttachmentPath($videoId, $attachmentId);

        return response()->file($absolutePath);
    }
}
