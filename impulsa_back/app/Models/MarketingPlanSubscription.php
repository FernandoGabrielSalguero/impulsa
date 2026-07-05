<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingPlanSubscription extends Model
{
    protected $table = 'marketing_plan_subscriptions';

    protected $fillable = [
        'plan_id',
        'pricing_option_id',
        'client_user_id',
        'entrepreneur_user_id',
        'assigned_marketing_user_id',
        'status',
        'payment_status',
        'payment_provider',
        'payment_reference',
        'payment_required',
        'duration_months',
        'monthly_price',
        'total_contract_value',
        'start_date',
        'end_date',
        'monthly_ad_budget',
        'notes',
        'activated_at',
    ];

    protected function casts(): array
    {
        return [
            'payment_required' => 'boolean',
            'duration_months' => 'integer',
            'monthly_price' => 'decimal:2',
            'total_contract_value' => 'decimal:2',
            'monthly_ad_budget' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
            'activated_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(MarketingPlan::class, 'plan_id');
    }

    public function pricingOption(): BelongsTo
    {
        return $this->belongsTo(MarketingPlanPricingOption::class, 'pricing_option_id');
    }

    public function clientUser(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'client_user_id');
    }

    public function entrepreneurUser(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'entrepreneur_user_id');
    }

    public function assignedMarketingUser(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'assigned_marketing_user_id');
    }
}
