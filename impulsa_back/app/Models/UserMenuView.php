<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMenuView extends Model
{
    protected $table = 'user_menu_view';

    protected $fillable = [
        'user_auth_id',
        'menu_key',
    ];

    public function userAuth(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'user_auth_id');
    }
}
