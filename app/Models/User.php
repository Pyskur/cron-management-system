<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'first_name',
        'last_name',
        'company_id',
        'country_id',
		'language',
        'stripe_cust_id',
        'balance'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function auto_renew()
    {
        return $this->belongsTo(AutoRenew::class);
    }

    public function isAdmin()
    {

        $groups = $this->groups;
        foreach ($groups as $key => $group) {
            if ($group->name == "administrators")
                return true;
        }
        return false;
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'users_companies');
    }
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'users_groups');
    }
}
