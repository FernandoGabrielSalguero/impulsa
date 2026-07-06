<?php

namespace App\Support;

use App\Models\ApiIntegration;
use Illuminate\Http\Request;

class PublicApiOriginValidator
{
    public static function isOriginAllowed(Request $request, string $allowedDomain): bool
    {
        $allowedHost = self::normalizeHost($allowedDomain);

        if ($allowedHost === '') {
            return false;
        }

        foreach (['Origin', 'Referer'] as $header) {
            $value = trim((string) $request->header($header, ''));

            if ($value === '') {
                continue;
            }

            $host = parse_url($value, PHP_URL_HOST);

            if (is_string($host) && self::hostsMatch($host, $allowedHost)) {
                return true;
            }
        }

        if (app()->environment('local', 'testing')) {
            return true;
        }

        return false;
    }

    public static function resolveAllowedOrigin(Request $request, ?ApiIntegration $integration = null): ?string
    {
        $origin = trim((string) $request->header('Origin', ''));

        if (app()->environment('local', 'testing') && $origin !== '') {
            return $origin;
        }

        if ($origin !== '') {
            $host = parse_url($origin, PHP_URL_HOST);

            if (is_string($host)) {
                if ($integration !== null && self::hostsMatch($host, self::normalizeHost($integration->allowed_domain))) {
                    return $origin;
                }

                if ($integration === null && self::originMatchesAnyActiveIntegration($request, $origin)) {
                    return $origin;
                }
            }
        }

        return null;
    }

    public static function originMatchesAnyActiveIntegration(Request $request, string $origin): bool
    {
        $host = parse_url($origin, PHP_URL_HOST);

        if (! is_string($host) || $host === '') {
            return false;
        }

        $allowedDomains = ApiIntegration::query()
            ->where('status', 'active')
            ->pluck('allowed_domain');

        foreach ($allowedDomains as $allowedDomain) {
            if (self::hostsMatch($host, self::normalizeHost((string) $allowedDomain))) {
                return true;
            }
        }

        return app()->environment('local', 'testing');
    }

    public static function normalizeHost(string $domain): string
    {
        $domain = trim(strtolower($domain));
        $domain = preg_replace('#^https?://#', '', $domain) ?? $domain;
        $domain = rtrim($domain, '/');

        return explode('/', $domain)[0] ?? '';
    }

    public static function hostsMatch(string $requestHost, string $allowedHost): bool
    {
        $requestHost = strtolower($requestHost);
        $allowedHost = strtolower($allowedHost);

        return $requestHost === $allowedHost
            || str_ends_with($requestHost, '.' . $allowedHost);
    }
}
