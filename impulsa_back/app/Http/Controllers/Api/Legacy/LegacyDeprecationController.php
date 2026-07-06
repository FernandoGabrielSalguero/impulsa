<?php

namespace App\Http\Controllers\Api\Legacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LegacyDeprecationController extends Controller
{
    public function gone(Request $request): JsonResponse|Response
    {
        if ($request->isMethod('OPTIONS')) {
            return response('', 410);
        }

        return response()->json([
            'message' => 'Este endpoint fue deprecado. Usá /api/v1/public/* con impulsa.js.',
            'code' => 'deprecated',
            'migration' => 'https://impulsagroup.com/api/v1/public/impulsa.js',
        ], 410, [
            'Deprecation' => 'true',
            'Link' => '</api/v1/public/bootstrap>; rel="successor-version"',
        ]);
    }

    public function deprecatedScript(): Response
    {
        $script = <<<'JS'
console.warn('[Impulsa] Este script fue deprecado. Usá /api/v1/public/impulsa.js');
JS;

        return response($script, 410, [
            'Content-Type' => 'application/javascript; charset=UTF-8',
            'Deprecation' => 'true',
        ]);
    }
}
