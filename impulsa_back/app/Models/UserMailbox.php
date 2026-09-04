<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class UserMailbox extends Model
{
    protected $table = 'user_mailboxes';

    protected $fillable = [
        'user_auth_id',
        'email',
        'password_encrypted',
        'enabled',
    ];

    protected $hidden = [
        'password_encrypted',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function userAuth(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'user_auth_id');
    }

    public function getPlainPassword(): string
    {
        return Crypt::decryptString((string) $this->password_encrypted);
    }

    public function setPlainPassword(string $password): void
    {
        $this->password_encrypted = Crypt::encryptString($password);
    }
}
