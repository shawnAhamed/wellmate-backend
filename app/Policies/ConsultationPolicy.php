<?php

namespace App\Policies;

use App\Models\Consultation;
use App\Models\User;

class ConsultationPolicy
{
    public function view(User $user, Consultation $consultation): bool
    {
        return $consultation->isParticipant($user) || $user->hasRole('admin');
    }

    public function cancel(User $user, Consultation $consultation): bool
    {
        return $this->view($user, $consultation);
    }

    /**
     * Confirming/completing a consultation is the doctor's call only.
     */
    public function manage(User $user, Consultation $consultation): bool
    {
        return $consultation->doctor?->user_id === $user->id;
    }
}
