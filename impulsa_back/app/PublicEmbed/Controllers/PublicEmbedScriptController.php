<?php

namespace App\PublicEmbed\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

class PublicEmbedScriptController extends Controller
{
    public function script(): Response
    {
        $candidates = [
            public_path('build/public-embed/impulsa.js'),
            public_path('build/assets/public-embed/impulsa.js'),
            resource_path('public-embed/dist/impulsa.js'),
        ];

        $path = null;
        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                $path = $candidate;
                break;
            }
        }

        if ($path === null) {
            return response('console.error("[Impulsa SDK] impulsa.js no compilado. Ejecutá npm run build en impulsa_back.");', 503, [
                'Content-Type' => 'application/javascript; charset=UTF-8',
            ]);
        }

        return response(File::get($path), 200, [
            'Content-Type' => 'application/javascript; charset=UTF-8',
            'Cache-Control' => 'public, max-age=300',
        ]);
    }
}
