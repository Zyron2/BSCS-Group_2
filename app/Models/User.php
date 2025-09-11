<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile',
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
}
