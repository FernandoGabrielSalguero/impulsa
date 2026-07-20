<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectDeliverableComment extends Model
{
    protected $table = 'project_deliverable_comments';

    protected $fillable = [
        'project_id',
        'deliverable_id',
        'user_auth_id',
        'message',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function userAuth(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'user_auth_id');
    }
}
