<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ConsultationRepositoryInterface extends RepositoryInterface
{
    public function forUser(int $userId, int $perPage = 10): LengthAwarePaginator;

    public function forDoctor(int $doctorId, int $perPage = 10): LengthAwarePaginator;

    public function existsForDoctorAt(int $doctorId, string $scheduledAt): bool;
}
