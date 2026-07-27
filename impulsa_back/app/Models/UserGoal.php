<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserGoal extends Model
{
    protected $table = 'user_goals';

    protected $fillable = [
        'user_auth_id',
        'title',
        'description',
        'start_date',
        'due_date',
        'status',
        'progress_percent',
        'completed_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'progress_percent' => 'integer',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'user_auth_id');
    }

    /** @return HasMany<UserGoalObjective> */
    public function objectives(): HasMany
    {
        return $this->hasMany(UserGoalObjective::class, 'goal_id')->orderBy('sort_order')->orderBy('id');
    }
}
