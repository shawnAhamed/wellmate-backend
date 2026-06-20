<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\DoctorAvailability;
use App\Repositories\Contracts\DoctorAvailabilityRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class DoctorAvailabilityService
{
    public function __construct(private DoctorAvailabilityRepositoryInterface $availabilities)
    {
    }

    public function listForDoctor(int $doctorId): Collection
    {
        return $this->availabilities->byDoctor($doctorId);
    }

    public function create(Doctor $doctor, array $data): DoctorAvailability
    {
        $this->assertNoOverlap($doctor->id, $data['day_of_week'], $data['start_time'], $data['end_time']);

        return $this->availabilities->create([
            'doctor_id' => $doctor->id,
            'day_of_week' => $data['day_of_week'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function update(DoctorAvailability $availability, array $data): DoctorAvailability
    {
        $dayOfWeek = $data['day_of_week'] ?? $availability->day_of_week;
        $startTime = $data['start_time'] ?? $availability->start_time;
        $endTime = $data['end_time'] ?? $availability->end_time;

        $this->assertNoOverlap($availability->doctor_id, $dayOfWeek, $startTime, $endTime, $availability->id);

        $this->availabilities->update($availability, $data);

        return $availability;
    }

    public function delete(DoctorAvailability $availability): void
    {
        $this->availabilities->delete($availability);
    }

    private function assertNoOverlap(int $doctorId, int $dayOfWeek, string $startTime, string $endTime, ?int $excludingId = null): void
    {
        if ($this->availabilities->overlapping($doctorId, $dayOfWeek, $startTime, $endTime, $excludingId)->isNotEmpty()) {
            throw ValidationException::withMessages([
                'start_time' => ['This slot overlaps with an existing availability window for that day.'],
            ]);
        }
    }
}
