<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class KeysCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'length',
        'otp_exp_time',
     ];

     protected $hidden = [
        'created_at',
        'updated_at',
    ];

}
