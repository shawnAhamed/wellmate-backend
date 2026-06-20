<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'user_id',
        'doctor_id',
        'scheduled_at',
        'duration_minutes',
        'status',
        'cancelled_by',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function messages()
    {
        return $this->hasMany(ConsultationMessage::class)->oldest();
    }

    public function isParticipant(User $user): bool
    {
        return $this->user_id === $user->id || $this->doctor?->user_id === $user->id;
    }
}
