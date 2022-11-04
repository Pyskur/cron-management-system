<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class HttpSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'authentication',
        'authentication_username',
        'authentication_password',
        'method',
        'message_body',
        'headers'
     ];

     protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
