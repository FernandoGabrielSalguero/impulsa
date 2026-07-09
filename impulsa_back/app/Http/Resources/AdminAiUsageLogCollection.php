<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AdminAiUsageLogCollection extends ResourceCollection
{
    public $collects = AdminAiUsageLogResource::class;

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
