<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalAccessToken extends Model
{
    protected $table = 'personal_access_tokens';

    public function tokenable()
    {
        return $this->morphTo();
    }
    public function checkToken(string $token): bool
    {
        return hash_equals($this->token, hash('sha256', $token));
    }
    public function scopeValid($query)
    {
        return $query->whereNull('expired_at');
    }
}
