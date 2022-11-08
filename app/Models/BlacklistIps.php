<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class BlacklistIps extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'enabled',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
