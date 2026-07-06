<?php

namespace App\Http\Middleware;

use App\Models\ApiIntegration;
use App\Support\PublicApiOriginValidator;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateLegacyPublicApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('OPTIONS')) {
            return $next($request);
        }

        $publicKey = trim((string) $request->input('public_key', ''));

        if ($publicKey === '') {
            return response()->json([
                'success' => false,
                'message' => 'public_key requerida.',
            ], 401);
        }

        $integration = ApiIntegration::query()
            ->where('public_key', $publicKey)
            ->first();

        if ($integration === null) {
            return response()->json([
                'success' => false,
                'message' => 'Integración no encontrada.',
            ], 404);
        }

        if ($integration->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Integración inactiva.',
            ], 403);
        }

        if (! PublicApiOriginValidator::isOriginAllowed($request, $integration->allowed_domain)) {
            return response()->json([
                'success' => false,
                'message' => 'Dominio no autorizado.',
            ], 403);
        }

        $integration->forceFill(['last_used_at' => now()])->save();

        $request->attributes->set('api_integration', $integration);

        return $next($request);
    }
}
