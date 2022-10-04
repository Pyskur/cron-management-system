<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeoGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_blacklist',
        'is_global',
        'aws_cloudfront_distribution_id'
    ];

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'country_geo_group_maps');
    }
    public function awsCloudfrontDistribution()
    {
        return $this->belongsTo(AwsCloudfrontDistribution::class);
    }

}
