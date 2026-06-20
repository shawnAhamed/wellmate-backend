<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface DoctorAvailabilityRepositoryInterface extends RepositoryInterface
{
    public function byDoctor(int $doctorId): Collection;

    /**
     * Active slots for the given doctor/day that overlap the given time
     * range, excluding the given availability id (used when updating).
     */
    public function overlapping(int $doctorId, int $dayOfWeek, string $startTime, string $endTime, ?int $excludingId = null): Collection;

    /**
     * Whether the doctor has an active slot on the given day that covers
     * the given time of day — used to validate consultation bookings.
     */
    public function coversTime(int $doctorId, int $dayOfWeek, string $time): bool;
}
