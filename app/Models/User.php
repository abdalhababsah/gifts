<?php

namespace App\Models;

use App\Mail\PasswordResetCodeMail;
use App\Mail\VerificationCodeMail;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'fcm_token',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'fcm_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the role that owns the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get all cart items for this user.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get all wishlist items for this user.
     */
    public function wishlistItems(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get all orders for this user.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get all support tickets for this user.
     */
    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    /**
     * Get all support ticket messages sent by this user.
     */
    public function supportTicketMessages(): HasMany
    {
        return $this->hasMany(SupportTicketMessage::class, 'sender_id');
    }

    /**
     * Get all verification codes for this user.
     */
    public function verificationCodes(): HasMany
    {
        return $this->hasMany(EmailVerificationCode::class);
    }

    /**
     * Send the email verification code.
     */
    public function sendEmailVerificationCode(): void
    {
        // Invalidate old verification codes
        $this->verificationCodes()
            ->where('used', false)
            ->update(['used' => true]);

        // Generate a new code
        $code = EmailVerificationCode::generateCode();

        // Store the code
        $this->verificationCodes()->create([
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(60), // Code expires in 60 minutes
        ]);

        // Send the code via email
        Mail::to($this->email)->send(new VerificationCodeMail($this, $code));
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->sendEmailVerificationCode();
    }

    /**
     * Send a password reset code to the user.
     */
    public static function sendPasswordResetCode(string $email): void
    {
        // Invalidate old password reset codes
        PasswordResetCode::where('email', $email)
            ->where('used', false)
            ->update(['used' => true]);

        // Generate a new code
        $code = PasswordResetCode::generateCode();

        // Store the code
        PasswordResetCode::create([
            'email' => $email,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(60), // Code expires in 60 minutes
        ]);

        // Send the code via email
        Mail::to($email)->send(new PasswordResetCodeMail($email, $code));
    }
}
