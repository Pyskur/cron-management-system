<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'amount',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
