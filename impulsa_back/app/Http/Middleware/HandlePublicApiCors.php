<?php

namespace App\Http\Middleware;

use App\Models\ApiIntegration;
use App\Support\PublicApiOriginValidator;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandlePublicApiCors
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('OPTIONS')) {
            $allowedOrigin = PublicApiOriginValidator::resolveAllowedOrigin($request);

            if ($allowedOrigin === null) {
                return response('', 403);
            }

            return $this->applyCorsHeaders(response('', 204), $allowedOrigin);
        }

        $response = $next($request);

        /** @var ApiIntegration|null $integration */
        $integration = $request->attributes->get('api_integration');

        $allowedOrigin = PublicApiOriginValidator::resolveAllowedOrigin($request, $integration);

        if ($allowedOrigin === null) {
            return $response;
        }

        return $this->applyCorsHeaders($response, $allowedOrigin);
    }

    private function applyCorsHeaders(Response $response, string $allowedOrigin): Response
    {
        $response->headers->set('Access-Control-Allow-Origin', $allowedOrigin);
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Origin');
        $response->headers->set('Access-Control-Max-Age', '86400');
        $response->headers->set('Vary', 'Origin');

        return $response;
    }
}
