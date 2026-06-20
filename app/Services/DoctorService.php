<?php

namespace App\Services;

use App\Models\Doctor;
use App\Repositories\Contracts\DoctorRepositoryInterface;
use App\Support\AuditLogger;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DoctorService
{
    public function __construct(
        private DoctorRepositoryInterface $doctors,
        private AuditLogger $auditLogger,
    ) {
    }

    public function listVerified(int $perPage = 12): LengthAwarePaginator
    {
        return $this->doctors->verified($perPage);
    }

    public function listPending(int $perPage = 10): LengthAwarePaginator
    {
        return $this->doctors->pending($perPage);
    }

    public function findVerifiedOrFail(int $id): Doctor
    {
        $doctor = $this->doctors->find($id, ['user']);

        if (! $doctor || ! $doctor->is_verified) {
            throw new ModelNotFoundException;
        }

        return $doctor;
    }

    public function verify(Doctor $doctor): Doctor
    {
        $this->doctors->update($doctor, [
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        $this->auditLogger->log('doctor.verified', $doctor, ['is_verified' => false], ['is_verified' => true]);

        return $doctor->load('user');
    }

    public function reject(Doctor $doctor): void
    {
        $user = $doctor->user;
        $user->removeRole('doctor');
        $user->assignRole('user');

        $this->auditLogger->log('doctor.rejected', $doctor, [], ['user_id' => $user->id]);

        $doctor->delete();
    }
}
