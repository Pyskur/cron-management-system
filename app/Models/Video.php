<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'filename',
        'status',
        'file_size',
        'geo_restrict',
        'thumbnail',
        'parent_name',
        'url',
        'drm_enabled',
        'user_id'
    ];
}
