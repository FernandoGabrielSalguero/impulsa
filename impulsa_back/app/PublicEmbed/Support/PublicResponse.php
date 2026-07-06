<?php

namespace App\PublicEmbed\Support;

use Illuminate\Http\JsonResponse;

final class PublicResponse
{
    /** @param array<string, mixed>|list<mixed>|null $data */
    public static function success(mixed $data = null, array $meta = [], int $status = 200): JsonResponse
    {
        $payload = ['data' => $data ?? new \stdClass()];

        if ($meta !== []) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $status);
    }

    public static function error(string $message, string $code, int $status = 400): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'code' => $code,
        ], $status);
    }
}
