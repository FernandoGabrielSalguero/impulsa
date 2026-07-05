<?php

namespace App\Http\Middleware;

use App\Models\ApiIntegration;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidatePublicApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $publicKey = trim((string) $request->query('public_key', ''));

        if ($publicKey === '') {
            return response()->json(['message' => 'public_key requerida.'], 401);
        }

        $integration = ApiIntegration::query()
            ->where('public_key', $publicKey)
            ->first();

        if ($integration === null) {
            return response()->json(['message' => 'Integración no encontrada.'], 404);
        }

        if (! $this->isOriginAllowed($request, $integration->allowed_domain)) {
            return response()->json(['message' => 'Dominio no autorizado.'], 403);
        }

        $integration->forceFill(['last_used_at' => now()])->save();

        $request->attributes->set('api_integration', $integration);

        return $next($request);
    }

    private function isOriginAllowed(Request $request, string $allowedDomain): bool
    {
        $allowedHost = $this->normalizeHost($allowedDomain);

        if ($allowedHost === '') {
            return false;
        }

        foreach (['Origin', 'Referer'] as $header) {
            $value = trim((string) $request->header($header, ''));

            if ($value === '') {
                continue;
            }

            $host = parse_url($value, PHP_URL_HOST);

            if (is_string($host) && $this->hostsMatch($host, $allowedHost)) {
                return true;
            }
        }

        // Permitir consultas server-side sin Origin en desarrollo.
        if (app()->environment('local', 'testing')) {
            return true;
        }

        return false;
    }

    private function normalizeHost(string $domain): string
    {
        $domain = trim(strtolower($domain));
        $domain = preg_replace('#^https?://#', '', $domain) ?? $domain;
        $domain = rtrim($domain, '/');

        return explode('/', $domain)[0] ?? '';
    }

    private function hostsMatch(string $requestHost, string $allowedHost): bool
    {
        $requestHost = strtolower($requestHost);
        $allowedHost = strtolower($allowedHost);

        return $requestHost === $allowedHost
            || str_ends_with($requestHost, '.' . $allowedHost);
    }
}
