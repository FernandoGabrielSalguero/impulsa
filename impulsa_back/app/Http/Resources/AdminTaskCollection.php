<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AdminTaskCollection extends ResourceCollection
{
    public $collects = AdminTaskResource::class;

    /** @param array<string, int> $summary */
    public function __construct($resource, private readonly array $summary = [])
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }

    public function with(Request $request): array
    {
        return [
            'summary' => $this->summary,
        ];
    }
}
