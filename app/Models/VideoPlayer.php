<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoPlayer extends Model
{
    use HasFactory;

    protected $fillable = [
        'config'
    ];

    protected $casts = [
        'config' => 'array'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
