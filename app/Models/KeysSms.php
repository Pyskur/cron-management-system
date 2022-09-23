<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeysSms extends Model
{
    use HasFactory;
    protected $table = 'keys_smses';
    protected $fillable = [
        'from',
        'text',
        'status',
        'friendly_name',
    ];
}
