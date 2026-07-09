<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExternalWebRequestCollection extends ResourceCollection
{
    public $collects = ExternalWebRequestResource::class;

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
