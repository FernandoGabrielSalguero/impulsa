<?php

namespace App\PublicEmbed\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ApiIntegration;
use App\PublicEmbed\Services\PublicChatbotService;
use App\PublicEmbed\Support\PublicResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PublicChatbotController extends Controller
{
    public function __construct(
        private readonly PublicChatbotService $chatbotService,
    ) {}

    public function show(Request $request): JsonResponse
    {
        /** @var ApiIntegration $integration */
        $integration = $request->attributes->get('api_integration');

        $config = $this->chatbotService->publicConfig($integration);

        if ($config === null) {
            return PublicResponse::success(null, [
                'feature' => 'chatbot',
                'status' => 'inactive',
            ]);
        }

        return PublicResponse::success($config, [
            'feature' => 'chatbot',
            'count' => count($config['nodes'] ?? []),
        ]);
    }

    public function avatar(Request $request): BinaryFileResponse|JsonResponse
    {
        /** @var ApiIntegration $integration */
        $integration = $request->attributes->get('api_integration');

        try {
            $file = $this->chatbotService->resolveAvatarFile($integration);
        } catch (ValidationException $exception) {
            return PublicResponse::error(
                collect($exception->errors())->flatten()->first() ?? 'Avatar no disponible.',
                'not_found',
                404,
            );
        }

        return response()->file($file['path'], [
            'Content-Type' => $file['mime'],
            'Cache-Control' => 'public, no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
        ]);
    }

    public function storeEvent(Request $request): JsonResponse
    {
        /** @var ApiIntegration $integration */
        $integration = $request->attributes->get('api_integration');

        $validated = $request->validate([
            'event_type' => ['required', 'string', 'max:40'],
            'node_id' => ['nullable', 'integer'],
            'option_id' => ['nullable', 'integer'],
            'page_url' => ['nullable', 'string', 'max:500'],
            'metadata' => ['nullable', 'array'],
        ]);

        try {
            $this->chatbotService->recordEvent($integration, $validated, $request);
        } catch (ValidationException $exception) {
            return PublicResponse::error(
                collect($exception->errors())->flatten()->first() ?? 'Evento inválido.',
                'validation_error',
                422,
            );
        }

        return PublicResponse::success(['ok' => true], [
            'feature' => 'chatbot',
            'message' => 'Evento registrado.',
        ], 201);
    }
}
