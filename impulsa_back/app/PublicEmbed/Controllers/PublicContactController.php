<?php

namespace App\PublicEmbed\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ApiIntegration;
use App\PublicEmbed\Services\PublicContactService;
use App\PublicEmbed\Support\PublicResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PublicContactController extends Controller
{
    public function __construct(
        private readonly PublicContactService $contactService,
    ) {}

    public function store(Request $request): JsonResponse
    {
        /** @var ApiIntegration $integration */
        $integration = $request->attributes->get('api_integration');

        $validated = $request->validate([
            'contact_nombre' => ['required', 'string', 'max:150'],
            'contact_email' => ['nullable', 'string', 'email', 'max:150'],
            'contact_whatsapp' => ['nullable', 'string', 'max:50'],
            'contact_description' => ['nullable', 'string'],
            'contact_consultation' => ['nullable', 'string', 'max:1000'],
            'page' => ['nullable', 'string', 'max:150'],
        ]);

        try {
            $id = $this->contactService->submit(
                $integration,
                $validated,
                (string) $request->input('page', '/'),
            );
        } catch (ValidationException $exception) {
            return PublicResponse::error(
                collect($exception->errors())->flatten()->first() ?? 'Datos inválidos.',
                'validation_error',
                422,
            );
        }

        return PublicResponse::success(['id' => $id, 'ok' => true], [
            'feature' => 'contact',
            'message' => 'Contacto recibido.',
        ], 201);
    }
}
