<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectCollaborator extends Model
{
    protected $table = 'project_collaborators';

    protected $fillable = [
        'project_id',
        'user_auth_id',
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
