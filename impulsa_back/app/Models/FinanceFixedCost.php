<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinanceFixedCost extends Model
{
    protected $table = 'finance_fixed_costs';

    protected $fillable = [
        'user_auth_id',
        'name',
        'amount',
        'frequency',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'float',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'user_auth_id');
    }

    public function monthlyAmount(): float
    {
        $amount = (float) $this->amount;

        return $this->frequency === 'anual' ? $amount / 12 : $amount;
    }
}
