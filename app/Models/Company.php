<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sso_token',
        'billing_detail',
        'address',
        'domain',
        'whitelist_ip',
        'logo',
        'color1',
        'color2',
        'color3'
    ];
}
