<?php

namespace App\Services;

use App\Models\Consultation;
use App\Models\ConsultationMessage;
use App\Models\User;
use App\Repositories\Contracts\ConsultationMessageRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class ConsultationMessageService
{
    public function __construct(private ConsultationMessageRepositoryInterface $messages)
    {
    }

    public function send(Consultation $consultation, User $sender, string $body): ConsultationMessage
    {
        if (! $consultation->isParticipant($sender)) {
            throw ValidationException::withMessages([
                'consultation' => ['You are not a participant in this consultation.'],
            ]);
        }

        return $this->messages->create([
            'consultation_id' => $consultation->id,
            'sender_id' => $sender->id,
            'body' => $body,
        ])->load('sender');
    }

    public function listFor(Consultation $consultation): Collection
    {
        return $this->messages->forConsultation($consultation->id);
    }
}
