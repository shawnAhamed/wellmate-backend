<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use App\Services\DoctorService;
use App\Support\ApiResponse;

class DoctorVerificationController extends Controller
{
    public function __construct(private DoctorService $doctorService)
    {
    }

    public function pending()
    {
        return ApiResponse::paginated(DoctorResource::collection($this->doctorService->listPending()));
    }

    public function verified()
    {
        return ApiResponse::paginated(DoctorResource::collection($this->doctorService->listVerified(10)));
    }

    public function verify(Doctor $doctor)
    {
        $doctor = $this->doctorService->verify($doctor);

        return ApiResponse::success(['doctor' => new DoctorResource($doctor)], 'Doctor verified successfully.');
    }

    public function reject(Doctor $doctor)
    {
        $this->doctorService->reject($doctor);

        return ApiResponse::success(null, 'Doctor application rejected.');
    }
}
