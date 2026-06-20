<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'password',
        'anonymous_handle',
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

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Display name shown publicly. Falls back to anonymous_handle
     * or a generic "Anonymous" label — never expose real name on
     * anonymous content.
     */
    public function displayNameFor(bool $isAnonymous): string
    {
        if (! $isAnonymous) {
            return $this->name;
        }

        return $this->anonymous_handle ?: 'Anonymous User';
    }
}
