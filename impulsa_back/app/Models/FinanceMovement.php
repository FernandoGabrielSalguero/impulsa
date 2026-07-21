<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinanceMovement extends Model
{
    protected $table = 'finance_movements';

    protected $fillable = [
        'user_auth_id',
        'type',
        'category_id',
        'amount',
        'occurred_on',
        'description',
        'product_id',
    ];

    protected $casts = [
        'amount' => 'float',
        'occurred_on' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'user_auth_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(FinanceCategory::class, 'category_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(ApiProduct::class, 'product_id');
    }
}
