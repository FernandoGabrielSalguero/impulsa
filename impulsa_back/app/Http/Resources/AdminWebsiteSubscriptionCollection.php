<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AdminWebsiteSubscriptionCollection extends ResourceCollection
{
    public $collects = AdminWebsiteSubscriptionResource::class;

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
