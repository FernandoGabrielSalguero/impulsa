<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAcademiaVideoRequest;
use App\Http\Requests\Admin\UpdateAcademiaVideoRequest;
use App\Http\Requests\Admin\UpdateAcademiaVideoStatusRequest;
use App\Http\Resources\AdminAcademiaVideoResource;
use App\Models\AcademiaVideo;
use App\Services\Admin\AcademiaAdminService;
use App\Support\AcademiaLabels;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AcademiaController extends Controller
{
    public function __construct(
        private readonly AcademiaAdminService $academiaAdminService,
    ) {}

    public function options(): JsonResponse
    {
        return response()->json([
            'statuses' => collect(AcademiaLabels::statuses())->map(static fn (string $value): array => [
                'value' => $value,
                'label' => AcademiaLabels::statusLabel($value),
            ])->values(),
        ]);
    }

    public function summary(): JsonResponse
    {
        return response()->json([
            'data' => $this->academiaAdminService->summary(),
        ]);
    }

    public function taxonomy(): JsonResponse
    {
        return response()->json([
            'data' => $this->academiaAdminService->taxonomy(),
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->academiaAdminService->list(
            $request->query('q'),
            $request->query('status'),
            $request->query('category'),
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

    public function store(StoreAcademiaVideoRequest $request): JsonResponse
    {
        $video = $this->academiaAdminService->create(
            $request->validated(),
            (int) $request->user()->id,
            $request->allFiles(),
        );

        return response()->json([
            'message' => 'Video de Academia creado correctamente.',
            'video' => new AdminAcademiaVideoResource($video),
        ], 201);
    }

    public function show(AcademiaVideo $academiaVideo): JsonResponse
    {
        return response()->json([
            'data' => new AdminAcademiaVideoResource($this->academiaAdminService->find((int) $academiaVideo->id)),
        ]);
    }

    public function update(UpdateAcademiaVideoRequest $request, AcademiaVideo $academiaVideo): JsonResponse
    {
        $video = $this->academiaAdminService->update(
            $academiaVideo,
            $request->validated(),
            $request->allFiles(),
        );

        return response()->json([
            'message' => 'Video de Academia actualizado correctamente.',
            'video' => new AdminAcademiaVideoResource($video),
        ]);
    }

    public function updateStatus(UpdateAcademiaVideoStatusRequest $request, AcademiaVideo $academiaVideo): JsonResponse
    {
        $video = $this->academiaAdminService->updateStatus(
            $academiaVideo,
            (string) $request->validated('status'),
        );

        return response()->json([
            'message' => 'Estado del video actualizado correctamente.',
            'video' => new AdminAcademiaVideoResource($video),
        ]);
    }

    public function destroy(AcademiaVideo $academiaVideo): JsonResponse
    {
        $this->academiaAdminService->delete($academiaVideo);

        return response()->json([
            'message' => 'Video de Academia eliminado correctamente.',
        ]);
    }

    public function downloadAttachment(AcademiaVideo $academiaVideo, int $attachmentId): BinaryFileResponse
    {
        $absolutePath = $this->academiaAdminService->resolveAttachmentPath($academiaVideo, $attachmentId);

        return response()->file($absolutePath);
    }
}
