<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MercadoPagoSubscriptionPlan extends Model
{
    protected $table = 'mercadopago_subscription_plans';

    protected $fillable = [
        'name',
        'amount',
        'payment_url',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function websiteSubscriptions(): HasMany
    {
        return $this->hasMany(WebsiteSubscription::class, 'mercadopago_subscription_plan_id');
    }

    public function marketingPricingOptions(): HasMany
    {
        return $this->hasMany(MarketingPlanPricingOption::class, 'mercadopago_subscription_plan_id');
    }
}
