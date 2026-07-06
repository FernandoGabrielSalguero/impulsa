<?php

namespace App\Http\Middleware;

use App\Models\ApiIntegration;
use App\Support\PublicApiOriginValidator;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidatePublicApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('OPTIONS')) {
            return $next($request);
        }

        $publicKey = $this->resolvePublicKey($request);

        if ($publicKey === '') {
            return response()->json([
                'message' => 'public_key requerida.',
                'code' => 'public_key_required',
            ], 401);
        }

        $integration = ApiIntegration::query()
            ->where('public_key', $publicKey)
            ->first();

        if ($integration === null) {
            return response()->json([
                'message' => 'Integración no encontrada.',
                'code' => 'integration_not_found',
            ], 404);
        }

        if ($integration->status !== 'active') {
            return response()->json([
                'message' => 'Integración inactiva.',
                'code' => 'integration_inactive',
            ], 403);
        }

        if (! PublicApiOriginValidator::isOriginAllowed($request, $integration->allowed_domain)) {
            return response()->json([
                'message' => 'Dominio no autorizado.',
                'code' => 'domain_not_allowed',
            ], 403);
        }

        $integration->forceFill(['last_used_at' => now()])->save();

        $request->attributes->set('api_integration', $integration);

        return $next($request);
    }

    private function resolvePublicKey(Request $request): string
    {
        $fromQuery = trim((string) $request->query('public_key', ''));

        if ($fromQuery !== '') {
            return $fromQuery;
        }

        $fromHeader = trim((string) $request->header('X-Impulsa-Public-Key', ''));

        if ($fromHeader !== '') {
            return $fromHeader;
        }

        return trim((string) $request->input('public_key', ''));
    }
}
