<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class CountryGeoGroupMap extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'geo_group_id'
    ];
}
