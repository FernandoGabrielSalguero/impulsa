<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AdminUserIngresoCollection extends ResourceCollection
{
    public $collects = AdminUserIngresoResource::class;

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
