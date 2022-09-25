<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoRenew extends Model
{
    use HasFactory;
    protected $fillable = [
        'auto_renew_min_amt',
        'auto_renew_amt',
        'auto_renewal'
    ];
}
