<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'doctor_id',
        'body',
        'is_accepted',
    ];

    protected function casts(): array
    {
        return [
            'is_accepted' => 'boolean',
        ];
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
