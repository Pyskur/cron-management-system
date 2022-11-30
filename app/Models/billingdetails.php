<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class billingdetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'amount',
        'user_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
