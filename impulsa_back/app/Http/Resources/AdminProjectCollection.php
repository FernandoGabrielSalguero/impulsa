<?php

namespace App\Http\Resources;

use App\Support\ProjectLabels;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AdminProjectCollection extends ResourceCollection
{
    public $collects = AdminProjectListResource::class;

    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'total' => $this->total(),
                'per_page' => $this->perPage(),
            ],
        ];
    }
}
