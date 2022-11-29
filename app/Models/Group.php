<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'permissions'
     ];

     protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

     public function users()
    {
        return $this->belongsToMany(User::class, 'users_groups');
    }
}
