<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'seller_id',
        'date',
        'amount',
    ];
    
    protected static function booted(): void
    {
        
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
