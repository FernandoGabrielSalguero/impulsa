<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserGoalObjective extends Model
{
    protected $table = 'user_goal_objectives';

    protected $fillable = [
        'goal_id',
        'title',
        'description',
        'due_date',
        'status',
        'sort_order',
        'completed_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'sort_order' => 'integer',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function goal(): BelongsTo
    {
        return $this->belongsTo(UserGoal::class, 'goal_id');
    }
}
