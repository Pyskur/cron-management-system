<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersApiHistories extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip',
        'api_path',
        'method',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
