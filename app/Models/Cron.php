<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Cron extends Model
{
    use HasFactory;

    protected $fillable = [
        'uniqueid',
        'name',
        'action',
        'expression',
        'date_last_run',
        'date_next_run',
        'next_cron_id',
        'user_id',
        'status',
        'is_running',
        'start_time',
        'end_time',
        'timezone',
        'location',
     ];

     protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
