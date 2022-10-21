<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Key extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'key',
        'level',
        'ignore_limits',
        'is_private_key',
        'ip_address',
        'otp_key',
        'video_enabled',
        'user_id'
     ];
}
