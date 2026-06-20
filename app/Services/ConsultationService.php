<?php

namespace App\Services;

use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\User;
use App\Repositories\Contracts\ConsultationRepositoryInterface;
use App\Repositories\Contracts\DoctorAvailabilityRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class ConsultationService
{
    public function __construct(
        private ConsultationRepositoryInterface $consultations,
        private DoctorAvailabilityRepositoryInterface $availabilities,
        private SubscriptionService $subscriptions,
    ) {
    }

    public function book(User $patient, Doctor $doctor, array $data): Consultation
    {
        $scheduledAt = Carbon::parse($data['scheduled_at']);

        if (! $doctor->is_verified) {
            throw ValidationException::withMessages([
                'doctor_id' => ['This doctor is not available for consultations.'],
            ]);
        }

        if (! $this->subscriptions->canBookConsultation($patient)) {
            throw ValidationException::withMessages([
                'plan' => ['Booking consultations requires a Monthly or Yearly subscription.'],
            ]);
        }

        if (! $this->availabilities->coversTime($doctor->id, $scheduledAt->dayOfWeek, $scheduledAt->format('H:i:s'))) {
            throw ValidationException::withMessages([
                'scheduled_at' => ['The doctor is not available at this time.'],
            ]);
        }

        if ($this->consultations->existsForDoctorAt($doctor->id, $scheduledAt->toDateTimeString())) {
            throw ValidationException::withMessages([
                'scheduled_at' => ['This slot has already been booked.'],
            ]);
        }

        return $this->consultations->create([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'scheduled_at' => $scheduledAt,
            'duration_minutes' => $data['duration_minutes'] ?? 30,
            'status' => 'requested',
        ])->load(['user', 'doctor.user']);
    }

    public function historyForUser(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return $this->consultations->forUser($user->id, $perPage);
    }

    public function historyForDoctor(Doctor $doctor, int $perPage = 10): LengthAwarePaginator
    {
        return $this->consultations->forDoctor($doctor->id, $perPage);
    }

    public function confirm(Consultation $consultation): Consultation
    {
        $this->assertTransitionAllowed($consultation, 'requested');

        return $this->transition($consultation, 'confirmed');
    }

    public function complete(Consultation $consultation): Consultation
    {
        $this->assertTransitionAllowed($consultation, 'confirmed');

        return $this->transition($consultation, 'completed');
    }

    public function cancel(Consultation $consultation, User $actor, ?string $reason): Consultation
    {
        if (in_array($consultation->status, ['completed', 'cancelled'], true)) {
            throw ValidationException::withMessages([
                'status' => ["A {$consultation->status} consultation can't be cancelled."],
            ]);
        }

        $this->consultations->update($consultation, [
            'status' => 'cancelled',
            'cancelled_by' => $actor->id,
            'cancellation_reason' => $reason,
        ]);

        return $consultation;
    }

    private function assertTransitionAllowed(Consultation $consultation, string $expectedCurrent): void
    {
        if ($consultation->status !== $expectedCurrent) {
            throw ValidationException::withMessages([
                'status' => ["Consultation must be '{$expectedCurrent}' for this action (currently '{$consultation->status}')."],
            ]);
        }
    }

    private function transition(Consultation $consultation, string $status): Consultation
    {
        $this->consultations->update($consultation, ['status' => $status]);

        return $consultation;
    }
}
