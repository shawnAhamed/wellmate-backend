<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Services\DoctorService;
use App\Support\ApiResponse;

class DoctorController extends Controller
{
    public function __construct(private DoctorService $doctorService)
    {
    }

    public function index()
    {
        return ApiResponse::paginated(DoctorResource::collection($this->doctorService->listVerified()));
    }

    public function show(int $doctor)
    {
        $doctor = $this->doctorService->findVerifiedOrFail($doctor);

        return ApiResponse::success(['doctor' => new DoctorResource($doctor)]);
    }
}
