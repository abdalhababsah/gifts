<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetCode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'code',
        'used',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'used' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Generate a new 5-digit verification code.
     */
    public static function generateCode(): string
    {
        return str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
    }

    /**
     * Check if the reset code is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
