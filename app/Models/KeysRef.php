<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class KeysRef extends Model
{
    use HasFactory;
    protected $fillable = [
        'ref1',
        'ref2',
        'ref3',
     ];
     protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
