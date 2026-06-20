<?php

namespace App\Repositories\Eloquent;

use App\Models\DoctorAvailability;
use App\Repositories\Contracts\DoctorAvailabilityRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentDoctorAvailabilityRepository extends BaseRepository implements DoctorAvailabilityRepositoryInterface
{
    public function __construct(DoctorAvailability $model)
    {
        parent::__construct($model);
    }

    public function byDoctor(int $doctorId): Collection
    {
        return $this->model->where('doctor_id', $doctorId)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
    }

    public function overlapping(int $doctorId, int $dayOfWeek, string $startTime, string $endTime, ?int $excludingId = null): Collection
    {
        return $this->model->where('doctor_id', $doctorId)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->when($excludingId, fn ($query) => $query->whereKeyNot($excludingId))
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->get();
    }

    public function coversTime(int $doctorId, int $dayOfWeek, string $time): bool
    {
        return $this->model->where('doctor_id', $doctorId)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->where('start_time', '<=', $time)
            ->where('end_time', '>', $time)
            ->exists();
    }
}
