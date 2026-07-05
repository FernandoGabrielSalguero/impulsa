<?php

namespace App\Http\Controllers\Api\V1\Emprendedor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Emprendedor\GenerateEstructuraRequest;
use App\Http\Requests\Emprendedor\SaveBuyerPersonaRequest;
use App\Http\Requests\Emprendedor\SaveLandingRequest;
use App\Http\Requests\Emprendedor\SaveMisionRequest;
use App\Http\Requests\Emprendedor\SaveVisionRequest;
use App\Services\Emprendedor\EmprendedorDefinicionAiService;
use App\Services\Emprendedor\EmprendedorDefinicionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DefinicionController extends Controller
{
    public function __construct(
        private readonly EmprendedorDefinicionService $definicionService,
        private readonly EmprendedorDefinicionAiService $definicionAiService,
    ) {}

    public function show(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->definicionService->show($request->user()),
        ]);
    }

    public function saveBuyerPersona(SaveBuyerPersonaRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Buyer persona guardado correctamente.',
            'buyer_persona' => $this->definicionService->saveBuyerPersona($request->user(), $request->validated()),
        ]);
    }

    public function saveMision(SaveMisionRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Misión guardada correctamente.',
            'mision' => $this->definicionService->saveMision($request->user(), $request->validated()),
        ]);
    }

    public function saveVision(SaveVisionRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Visión guardada correctamente.',
            'vision' => $this->definicionService->saveVision($request->user(), $request->validated()),
        ]);
    }

    public function saveLanding(SaveLandingRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Datos del emprendimiento guardados correctamente.',
            'landing' => $this->definicionService->saveLanding($request->user(), $request->validated()),
        ]);
    }

    public function generateEstructura(GenerateEstructuraRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $result = $this->definicionAiService->generate(
            $request->user(),
            (string) $validated['type'],
            (array) $validated['fields'],
            (bool) ($validated['prefer_ai'] ?? true),
            $request->ip(),
        );

        return response()->json([
            'data' => $result,
        ]);
    }
}
