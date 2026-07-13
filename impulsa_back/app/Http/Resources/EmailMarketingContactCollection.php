<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EmailMarketingContactCollection extends ResourceCollection
{
    public $collects = EmailMarketingContactResource::class;

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
