<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AdminChatbotCollection extends ResourceCollection
{
    public $collects = AdminChatbotResource::class;

    /** @param array<string, mixed> $summary */
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
