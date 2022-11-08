<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class AwsCloudfrontDistribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'dist_id',
        'description',
        'domain_name',
        'alt_domain_name',
        'origin'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
