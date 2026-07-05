<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    protected $fillable = [
        'source_type',
        'source_id',
        'project_name',
        'project_type',
        'client_user_id',
        'manager_user_id',
        'client_name',
        'client_email',
        'client_whatsapp',
        'summary',
        'scope_summary',
        'status',
        'priority',
        'start_date',
        'target_delivery_date',
        'actual_delivery_date',
        'progress_percent',
        'client_visible',
    ];

    protected $casts = [
            'client_visible' => 'boolean',
            'start_date' => 'date',
            'target_delivery_date' => 'date',
            'actual_delivery_date' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];

    public function clientUser(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'client_user_id');
    }

    public function managerUser(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'manager_user_id');
    }
}
