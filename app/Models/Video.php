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
        'drm_enabled',
        'user_id',
        'uuid',
        'src_url',
        'out_url',
        'out_folder',
        'out_folder_size',
    ];
}
