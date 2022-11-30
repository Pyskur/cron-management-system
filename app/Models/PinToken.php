<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'referable_id',
        'referable_type', 
        'reference_type',
        'reference_token',
        'pin',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime'
    ];

    public function referable()
    {
        return $this->morphTo();
    }

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