<?php

namespace App\PublicEmbed\Controllers;

use App\Http\Controllers\Controller;
use App\PublicEmbed\Services\PublicMediaFileService;
use App\PublicEmbed\Support\PublicResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PublicMediaController extends Controller
{
    public function __construct(
        private readonly PublicMediaFileService $mediaFileService,
    ) {}

    public function show(string $path): BinaryFileResponse|JsonResponse
    {
        try {
            $media = $this->mediaFileService->resolve($path);
        } catch (ValidationException $exception) {
            return PublicResponse::error(
                collect($exception->errors())->flatten()->first() ?? 'Archivo no encontrado.',
                'not_found',
                404,
            );
        }

        return response()->file($media['path'], [
            'Content-Type' => $media['mime'],
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
