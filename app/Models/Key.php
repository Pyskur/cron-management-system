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
        'permissions',
        'level',
        'ignore_limits',
        'is_private_key',
        'ip_address',
        'user_id',
        'keys_sms_id'
    ];

    protected $casts = [
        'ip_address' => 'array',
        'permissions' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function keys_sms()
    {
        return $this->belongsTo(KeysSms::class);
    }
}
