<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class Seller extends Model
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'name',
        'email',
        'password'
    ];
    protected $hidden = [
        'password'
    ];

    protected static function booted(): void
    {

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
