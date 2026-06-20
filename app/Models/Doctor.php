<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialization',
        'bio',
        'license_number',
        'license_number_hash',
        'is_verified',
        'verified_at',
    ];

    protected $hidden = [
        'license_number_hash',
    ];

    protected function casts(): array
    {
        return [
            'license_number' => 'encrypted',
            'is_verified' => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    /**
     * Deterministic HMAC used as a "blind index" for uniqueness checks,
     * since the encrypted license_number column can't be compared via SQL.
     */
    public static function hashLicenseNumber(string $value): string
    {
        return hash_hmac('sha256', $value, config('app.key'));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function availabilities()
    {
        return $this->hasMany(DoctorAvailability::class);
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_verified', false);
    }
}
