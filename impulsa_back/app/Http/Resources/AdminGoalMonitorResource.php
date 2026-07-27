<?php

namespace App\Http\Resources;

use App\Services\Admin\AdminGoalsMonitorService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\UserGoal */
class AdminGoalMonitorResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return app(AdminGoalsMonitorService::class)->serializeListRow($this->resource);
    }
}
