<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AdminGoalMonitorCollection extends ResourceCollection
{
    public $collects = AdminGoalMonitorResource::class;

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
