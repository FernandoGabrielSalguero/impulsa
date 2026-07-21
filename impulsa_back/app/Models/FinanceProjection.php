<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinanceProjection extends Model
{
    protected $table = 'finance_projections';

    protected $fillable = [
        'user_auth_id',
        'name',
        'months',
        'assumptions_json',
        'series_json',
        'notes',
    ];

    protected $casts = [
        'months' => 'integer',
        'assumptions_json' => 'array',
        'series_json' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'user_auth_id');
    }
}
