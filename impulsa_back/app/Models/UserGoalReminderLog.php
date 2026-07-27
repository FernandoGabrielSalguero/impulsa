<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserGoalReminderLog extends Model
{
    protected $table = 'user_goal_reminder_logs';

    protected $fillable = [
        'user_auth_id',
        'entity_type',
        'entity_id',
        'reminder_kind',
        'sent_on',
    ];

    protected $casts = [
        'entity_id' => 'integer',
        'sent_on' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'user_auth_id');
    }
}
