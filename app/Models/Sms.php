<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Sms extends Model
{
    use HasFactory;

    protected $fillable = [
        'number_to_send',
        'uniqueid',
        'date_expires',
        'status',
        'cost',
        'charge',
        'date_added',
        'user_id',
        'log',
        'key_id',
        'code_variable',
    ];
}
