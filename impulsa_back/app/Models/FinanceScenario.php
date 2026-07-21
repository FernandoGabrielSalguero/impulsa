<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinanceScenario extends Model
{
    protected $table = 'finance_scenarios';

    protected $fillable = [
        'user_auth_id',
        'name',
        'description',
        'is_baseline',
        'months',
        'assumptions_json',
        'result_json',
    ];

    protected $casts = [
        'is_baseline' => 'boolean',
        'months' => 'integer',
        'assumptions_json' => 'array',
        'result_json' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'user_auth_id');
    }
}
