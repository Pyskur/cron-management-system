<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class UsersNotifications extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'notification_id',
    ];
}
