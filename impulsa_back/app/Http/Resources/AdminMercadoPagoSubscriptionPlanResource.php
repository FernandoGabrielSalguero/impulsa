<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminMercadoPagoSubscriptionPlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'amount' => (float) $this->amount,
            'payment_url' => $this->payment_url,
            'status' => $this->status,
            'status_label' => $this->status === 'active' ? 'Activo' : 'Inactivo',
            'notes' => $this->notes,
            'subscriptions_count' => (int) ($this->website_subscriptions_count ?? 0),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
