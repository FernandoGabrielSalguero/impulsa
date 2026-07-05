<?php

namespace App\Http\Controllers\Api\V1\Webhooks;

use App\Http\Controllers\Controller;
use App\Services\WebsiteSubscription\MercadoPagoWebhookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MercadoPagoWebhookController extends Controller
{
    public function __construct(
        private readonly MercadoPagoWebhookService $mercadoPagoWebhookService,
    ) {}

    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        if ($payload === [] && $request->getContent() !== '') {
            $decoded = json_decode($request->getContent(), true);
            $payload = is_array($decoded) ? $decoded : [];
        }

        if ($request->query->has('type') || $request->query->has('data.id')) {
            $payload = array_merge($payload, [
                'type' => $request->query('type', $payload['type'] ?? null),
                'data' => [
                    'id' => $request->query('data.id', $payload['data']['id'] ?? null),
                ],
            ]);
        }

        try {
            $this->mercadoPagoWebhookService->handle($payload);
        } catch (\Throwable $exception) {
            Log::error('MercadoPago webhook error', [
                'error' => $exception->getMessage(),
                'payload' => $payload,
            ]);
        }

        return response()->json(['received' => true]);
    }
}
