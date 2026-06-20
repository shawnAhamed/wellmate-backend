<?php

namespace App\Repositories\Eloquent;

use App\Models\Doctor;
use App\Repositories\Contracts\DoctorRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentDoctorRepository extends BaseRepository implements DoctorRepositoryInterface
{
    public function __construct(Doctor $model)
    {
        parent::__construct($model);
    }

    public function findByUserId(int $userId): ?Doctor
    {
        return $this->model->where('user_id', $userId)->first();
    }

    public function pending(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with('user')->pending()->latest()->paginate($perPage);
    }

    public function verified(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with('user')->verified()->latest()->paginate($perPage);
    }
}
