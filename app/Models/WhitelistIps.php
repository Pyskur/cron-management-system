<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class WhitelistIps extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'enabled',
    ];
}
