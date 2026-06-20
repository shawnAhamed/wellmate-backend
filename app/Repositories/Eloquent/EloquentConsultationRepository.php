<?php

namespace App\Repositories\Eloquent;

use App\Models\Consultation;
use App\Repositories\Contracts\ConsultationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentConsultationRepository extends BaseRepository implements ConsultationRepositoryInterface
{
    public function __construct(Consultation $model)
    {
        parent::__construct($model);
    }

    public function forUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with('doctor.user')
            ->where('user_id', $userId)
            ->latest('scheduled_at')
            ->paginate($perPage);
    }

    public function forDoctor(int $doctorId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with('user')
            ->where('doctor_id', $doctorId)
            ->latest('scheduled_at')
            ->paginate($perPage);
    }

    public function existsForDoctorAt(int $doctorId, string $scheduledAt): bool
    {
        return $this->model->where('doctor_id', $doctorId)
            ->where('scheduled_at', $scheduledAt)
            ->whereNotIn('status', ['cancelled'])
            ->exists();
    }
}
