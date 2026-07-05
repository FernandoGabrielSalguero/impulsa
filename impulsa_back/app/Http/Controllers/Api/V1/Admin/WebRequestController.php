<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExternalWebRequestCollection;
use App\Http\Resources\ExternalWebRequestDetailResource;
use App\Http\Resources\InternalWebRequestCollection;
use App\Http\Resources\InternalWebRequestDetailResource;
use App\Services\Admin\WebRequestActionService;
use App\Services\Admin\WebRequestAdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebRequestController extends Controller
{
    public function __construct(
        private readonly WebRequestAdminService $webRequestAdminService,
        private readonly WebRequestActionService $webRequestActionService,
    ) {}

    public function indexInternal(Request $request): InternalWebRequestCollection
    {
        $perPage = max(1, min((int) $request->integer('per_page', 20), 100));

        return new InternalWebRequestCollection(
            $this->webRequestAdminService->listInternal($request->query('q'), $perPage),
        );
    }

    public function showInternal(int $webRequest): InternalWebRequestDetailResource|JsonResponse
    {
        $solicitud = $this->webRequestAdminService->findInternal($webRequest);

        if ($solicitud === null) {
            return response()->json(['message' => 'Solicitud no encontrada.'], 404);
        }

        return new InternalWebRequestDetailResource($solicitud);
    }

    public function indexExternal(Request $request): ExternalWebRequestCollection
    {
        $perPage = max(1, min((int) $request->integer('per_page', 20), 100));

        return new ExternalWebRequestCollection(
            $this->webRequestAdminService->listExternal($request->query('q'), $perPage),
        );
    }

    public function showExternal(int $webRequest): ExternalWebRequestDetailResource|JsonResponse
    {
        $solicitud = $this->webRequestAdminService->findExternal($webRequest);

        if ($solicitud === null) {
            return response()->json(['message' => 'Solicitud no encontrada.'], 404);
        }

        return new ExternalWebRequestDetailResource($solicitud);
    }

    public function createUserFromExternal(int $webRequest, Request $request): JsonResponse
    {
        $solicitud = $this->webRequestAdminService->findExternal($webRequest);

        if ($solicitud === null) {
            return response()->json(['message' => 'Solicitud no encontrada.'], 404);
        }

        $result = $this->webRequestActionService->createUserFromExternal($solicitud);

        return response()->json($result, $result['ok'] ? 200 : 422);
    }

    public function createProjectFromInternal(int $webRequest, Request $request): JsonResponse
    {
        $solicitud = $this->webRequestAdminService->findInternal($webRequest);

        if ($solicitud === null) {
            return response()->json(['message' => 'Solicitud no encontrada.'], 404);
        }

        $result = $this->webRequestActionService->createProjectFromInternal(
            $solicitud,
            (int) $request->user()->id,
        );

        return response()->json($result, $result['ok'] ? 200 : 422);
    }

    public function createProjectFromExternal(int $webRequest, Request $request): JsonResponse
    {
        $solicitud = $this->webRequestAdminService->findExternal($webRequest);

        if ($solicitud === null) {
            return response()->json(['message' => 'Solicitud no encontrada.'], 404);
        }

        $result = $this->webRequestActionService->createProjectFromExternal(
            $solicitud,
            (int) $request->user()->id,
        );

        return response()->json($result, $result['ok'] ? 200 : 422);
    }
}
