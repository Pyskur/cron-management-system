<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Epd extends Model
{
    use HasFactory;
    protected $fillable = [
        'epd',
        'epd_interval',
        'timeout',
        'epd_daily',
        'service_type'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
