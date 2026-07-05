<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebsiteSubscriptionPeriod extends Model
{
    protected $fillable = [
        'website_subscription_id',
        'year',
        'month',
        'amount',
        'status',
        'mercadopago_payment_id',
        'paid_at',
        'first_notice_sent_at',
        'last_reminder_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'month' => 'integer',
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'first_notice_sent_at' => 'datetime',
            'last_reminder_sent_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(WebsiteSubscription::class, 'website_subscription_id');
    }

    public function periodKey(): string
    {
        return sprintf('%04d-%02d', $this->year, $this->month);
    }
}
