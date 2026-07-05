<?php

namespace App\Http\Resources;

use App\Support\WebsiteSubscriptionLabels;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminWebsiteSubscriptionPeriodResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'year' => (int) $this->year,
            'month' => (int) $this->month,
            'period' => $this->periodKey(),
            'amount' => (float) $this->amount,
            'status' => $this->status,
            'status_label' => WebsiteSubscriptionLabels::periodStatusLabel($this->status),
            'mercadopago_payment_id' => $this->mercadopago_payment_id,
            'paid_at' => $this->paid_at?->toISOString(),
            'first_notice_sent_at' => $this->first_notice_sent_at?->toISOString(),
            'last_reminder_sent_at' => $this->last_reminder_sent_at?->toISOString(),
        ];
    }
}
