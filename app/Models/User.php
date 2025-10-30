<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_picture',
        'settings', // Add this
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'settings' => 'array', // Add this to cast JSON to array
        ];
    }

    /**
     * Accessor for profile picture URL.
     */
    public function getProfileUrlAttribute(): string
    {
        if (!empty($this->profile) && str_starts_with($this->profile, '/storage/')) {
            return asset($this->profile);
        }

        // fallback to default avatar
        return asset('default-avatar.png'); // ilagay mo ito sa public/default-avatar.png
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
