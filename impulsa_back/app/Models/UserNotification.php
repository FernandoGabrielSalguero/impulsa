<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotification extends Model
{
    protected $table = 'user_notifications';

    protected $fillable = [
        'user_auth_id',
        'type',
        'title',
        'body',
        'payload',
        'read_at',
        'dismissed_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'read_at' => 'datetime',
            'dismissed_at' => 'datetime',
        ];
    }

    public function userAuth(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'user_auth_id');
    }
}
