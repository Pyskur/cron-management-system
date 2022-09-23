<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeysRef extends Model
{
    use HasFactory;
    protected $fillable = [
        'ref1',
        'ref2',
        'ref3',
     ];
}
