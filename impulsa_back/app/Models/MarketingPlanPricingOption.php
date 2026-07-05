<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketingPlanPricingOption extends Model
{
    protected $table = 'marketing_plan_pricing_options';

    protected $fillable = [
        'plan_id',
        'duration_months',
        'monthly_price',
        'total_price',
        'setup_fee',
        'currency',
        'mercadopago_subscription_plan_id',
        'is_featured',
        'is_default',
        'display_order',
    ];

    protected $casts = [
            'duration_months' => 'integer',
            'monthly_price' => 'decimal:2',
            'total_price' => 'decimal:2',
            'setup_fee' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_default' => 'boolean',
            'display_order' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(MarketingPlan::class, 'plan_id');
    }

    public function mercadopagoPlan(): BelongsTo
    {
        return $this->belongsTo(MercadoPagoSubscriptionPlan::class, 'mercadopago_subscription_plan_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(MarketingPlanSubscription::class, 'pricing_option_id');
    }
}
