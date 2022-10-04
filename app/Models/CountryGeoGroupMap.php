<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryGeoGroupMap extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'geo_group_id'
    ];
}
