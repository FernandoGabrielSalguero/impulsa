<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AdminTaskCollection extends ResourceCollection
{
    public $collects = AdminTaskResource::class;

    /** @param  array<string, int>  $summary */
    public function __construct($resource, private readonly array $summary = [])
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'total' => $this->total(),
                'per_page' => $this->perPage(),
                'summary' => $this->summary,
            ],
        ];
    }
}
