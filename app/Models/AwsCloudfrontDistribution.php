<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
