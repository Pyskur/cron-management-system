<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Limit extends Model
{
    use HasFactory;

    protected $fillable = [
        'uri',
        'count',
        'hour_started',
        'api_key'
     ];
}
