<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiIntegration extends Model
{
    protected $table = 'api_integrations';

    protected $fillable = [
        'project_name',
        'allowed_domain',
        'public_key',
        'secret_key_hash',
        'status',
        'user_auth_id',
        'last_used_at',
    ];

    protected $hidden = [
        'secret_key_hash',
    ];

    protected $casts = [
            'last_used_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'user_auth_id');
    }
}
