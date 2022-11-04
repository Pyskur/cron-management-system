<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_name',
        'email',
        'plan_id',
        'plan_name',
        'payment_response',
        'payment_status',
        'created_date',
        'amount',
        'client_secret',
        'fingerprint',
        'charge_id',
        'customer_id',
        'currency',
        'exp_month',
        'exp_year',
        'card_st_digit',
        'user_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
