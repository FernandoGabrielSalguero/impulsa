<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketingPlan extends Model
{
    protected $table = 'marketing_plans';

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'full_description',
        'objective',
        'recommended_ad_budget_min',
        'recommended_ad_budget_max',
        'setup_fee',
        'billing_period',
        'report_frequency',
        'support_level',
        'is_visible_to_clients',
        'status',
        'created_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'recommended_ad_budget_min' => 'decimal:2',
            'recommended_ad_budget_max' => 'decimal:2',
            'setup_fee' => 'decimal:2',
            'is_visible_to_clients' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function features(): HasMany
    {
        return $this->hasMany(MarketingPlanFeature::class, 'plan_id')->orderBy('feature_order')->orderBy('id');
    }

    public function pricingOptions(): HasMany
    {
        return $this->hasMany(MarketingPlanPricingOption::class, 'plan_id')->orderBy('display_order')->orderBy('duration_months');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(MarketingPlanSubscription::class, 'plan_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'created_by_user_id');
    }
}
