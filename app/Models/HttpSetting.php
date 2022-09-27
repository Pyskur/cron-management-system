<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HttpSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'authentication',
        'authentication_username',
        'authentication_password',
        'method',
        'message_body',
        'headers'
     ];
}
