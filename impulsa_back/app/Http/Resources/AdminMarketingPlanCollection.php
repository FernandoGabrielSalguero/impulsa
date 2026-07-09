<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AdminMarketingPlanCollection extends ResourceCollection
{
    public $collects = AdminMarketingPlanResource::class;

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
