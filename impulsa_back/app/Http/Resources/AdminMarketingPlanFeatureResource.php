<?php

namespace App\Http\Resources;

use App\Models\MarketingPlanFeature;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminMarketingPlanFeatureResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var MarketingPlanFeature $feature */
        $feature = $this->resource;

        return [
            'id' => (int) $feature->id,
            'feature_name' => (string) $feature->feature_name,
            'feature_description' => $feature->feature_description,
            'quantity' => $feature->quantity !== null ? (float) $feature->quantity : null,
            'unit' => $feature->unit,
            'feature_order' => (int) $feature->feature_order,
            'is_highlighted' => (bool) $feature->is_highlighted,
        ];
    }
}
