<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingPlanFeature extends Model
{
    protected $table = 'marketing_plan_features';

    protected $fillable = [
        'plan_id',
        'feature_name',
        'feature_description',
        'quantity',
        'unit',
        'feature_order',
        'is_highlighted',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'feature_order' => 'integer',
            'is_highlighted' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(MarketingPlan::class, 'plan_id');
    }
}
