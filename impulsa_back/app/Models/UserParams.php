<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserParams extends Model
{
    protected $table = 'user_params';

    protected $primaryKey = 'user_auth_id';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'user_auth_id',
        'page',
    ];

    public function userAuth(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'user_auth_id');
    }
}
