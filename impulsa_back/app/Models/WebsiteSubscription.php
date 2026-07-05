<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WebsiteSubscription extends Model
{
    protected $fillable = [
        'api_integration_id',
        'status',
        'mercadopago_preapproval_id',
        'mercadopago_subscription_plan_id',
        'grace_months_count',
        'default_amount',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'grace_months_count' => 'integer',
            'default_amount' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function apiIntegration(): BelongsTo
    {
        return $this->belongsTo(ApiIntegration::class, 'api_integration_id');
    }

    public function mercadopagoPlan(): BelongsTo
    {
        return $this->belongsTo(MercadoPagoSubscriptionPlan::class, 'mercadopago_subscription_plan_id');
    }

    public function periods(): HasMany
    {
        return $this->hasMany(WebsiteSubscriptionPeriod::class, 'website_subscription_id');
    }
}
