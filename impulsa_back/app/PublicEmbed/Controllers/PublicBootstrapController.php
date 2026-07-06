<?php

namespace App\PublicEmbed\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ApiIntegration;
use App\PublicEmbed\Support\FeatureCatalog;
use App\PublicEmbed\Support\PublicResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicBootstrapController extends Controller
{
    public function __construct(
        private readonly FeatureCatalog $featureCatalog,
    ) {}

    public function show(Request $request): JsonResponse
    {
        /** @var ApiIntegration $integration */
        $integration = $request->attributes->get('api_integration');

        return PublicResponse::success(
            $this->featureCatalog->bootstrap($integration),
            ['feature' => 'bootstrap'],
        );
    }
}
