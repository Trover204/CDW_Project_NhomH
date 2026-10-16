<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\LogsActivity;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'phone', 'avatar', 'birthday', 'role', 'status'];

    protected $hidden = ['password', 'remember_token'];

    /** @use HasFactory<UserFactory> */
    use HasFactory, LogsActivity, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birthday' => 'date',
            'password' => 'hashed',
        ];
    }

    public function favoriteCourts(): BelongsToMany
    {
        return $this->belongsToMany(Court::class, 'court_user_likes')->withTimestamps();
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
