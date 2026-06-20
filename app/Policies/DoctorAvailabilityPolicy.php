<?php

namespace App\Policies;

use App\Models\DoctorAvailability;
use App\Models\User;

class DoctorAvailabilityPolicy
{
    public function update(User $user, DoctorAvailability $availability): bool
    {
        return $user->doctor && $user->doctor->id === $availability->doctor_id;
    }

    public function delete(User $user, DoctorAvailability $availability): bool
    {
        return $this->update($user, $availability);
    }
}
