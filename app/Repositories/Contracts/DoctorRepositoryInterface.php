<?php

namespace App\Repositories\Contracts;

use App\Models\Doctor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DoctorRepositoryInterface extends RepositoryInterface
{
    public function findByUserId(int $userId): ?Doctor;

    public function pending(int $perPage = 15): LengthAwarePaginator;

    public function verified(int $perPage = 15): LengthAwarePaginator;
}
