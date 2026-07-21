<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancePricingItem extends Model
{
    protected $table = 'finance_pricing_items';

    protected $fillable = [
        'user_auth_id',
        'name',
        'variable_cost',
        'extra_costs',
        'mode',
        'target_percent',
        'suggested_price',
        'notes',
        'product_id',
    ];

    protected $casts = [
        'variable_cost' => 'float',
        'extra_costs' => 'float',
        'target_percent' => 'float',
        'suggested_price' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'user_auth_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(ApiProduct::class, 'product_id');
    }
}
