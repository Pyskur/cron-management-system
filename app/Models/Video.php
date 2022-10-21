<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

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
        'geo_group_id',
        'publish_date',
        'unpublish_date',
        'thumbnail_count'
    ];

    public function geoGroup()
    {
        return $this->belongsTo(GeoGroup::class);
    }
    public function isPublished()
    {
        if($this->publish_date){
            $now = new \DateTime("now");
            $publishDate = new \DateTime($this->publish_date);
            if($publishDate >= $now)
                return true;
            else
                return false;
        }
        return true;
    }
    public function isExpired()
    {
        if($this->unpublish_date){
            $now = new \DateTime("now");
            $unPublishDate = new \DateTime($this->unpublish_date);
            if($unPublishDate < $now)
                return true;
        }
        return false;
    }
}
