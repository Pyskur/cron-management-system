<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class UsersLoginIps extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'login_ip',
        'login_at',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
