<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Services\Profile\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProfileController extends Controller
{
    public function __construct(
        private readonly ProfileService $profileService,
    ) {}

    public function show(Request $request): ProfileResource
    {
        return new ProfileResource($this->profileService->show($request->user()));
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $this->profileService->update(
            $request->user(),
            $request->validated(),
            $request->file('avatar_file'),
        );

        return response()->json([
            'message' => 'Perfil actualizado correctamente.',
            'profile' => new ProfileResource($user),
        ]);
    }

    public function avatar(Request $request): BinaryFileResponse|JsonResponse
    {
        try {
            $file = $this->profileService->resolveAvatarFile($request->user());
        } catch (ValidationException $exception) {
            return response()->json([
                'message' => collect($exception->errors())->flatten()->first() ?? 'Avatar no disponible.',
            ], 404);
        }

        if (! is_file($file['path'])) {
            return response()->json(['message' => 'Avatar no encontrado.'], 404);
        }

        return response()->file($file['path'], [
            'Content-Type' => $file['mime'],
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }
}
