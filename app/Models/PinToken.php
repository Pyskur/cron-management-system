<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'mobile_number',
        'passcode',
        'expired_at',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime'
    ];

    public function isExpired()
    {
        return $this->expired_at->isPast();
    }

    public function extendExpiry($minutes = 2)
    {
        $this->update([
            'expired_at' => now()->addMinutes($minutes)
        ]);
    }
}