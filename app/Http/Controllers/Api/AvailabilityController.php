<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAvailabilityRequest;
use App\Http\Requests\UpdateAvailabilityRequest;
use App\Http\Resources\DoctorAvailabilityResource;
use App\Models\DoctorAvailability;
use App\Services\DoctorAvailabilityService;
use App\Services\DoctorService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AvailabilityController extends Controller
{
    public function __construct(
        private DoctorAvailabilityService $availabilityService,
        private DoctorService $doctorService,
    ) {
    }

    /**
     * Public — a patient browsing a doctor's profile needs to see when
     * they're available, ahead of Phase 4's consultation booking.
     */
    public function index(int $doctor)
    {
        $doctor = $this->doctorService->findVerifiedOrFail($doctor);

        $resource = DoctorAvailabilityResource::collection($this->availabilityService->listForDoctor($doctor->id));

        return ApiResponse::success(['availability' => $resource]);
    }

    public function mine(Request $request)
    {
        $resource = DoctorAvailabilityResource::collection(
            $this->availabilityService->listForDoctor($request->user()->doctor->id)
        );

        return ApiResponse::success(['availability' => $resource]);
    }

    public function store(StoreAvailabilityRequest $request)
    {
        $availability = $this->availabilityService->create($request->user()->doctor, $request->validated());

        return ApiResponse::success(['availability' => new DoctorAvailabilityResource($availability)], 'Availability slot added.', 201);
    }

    public function update(UpdateAvailabilityRequest $request, DoctorAvailability $availability)
    {
        Gate::authorize('update', $availability);

        $availability = $this->availabilityService->update($availability, $request->validated());

        return ApiResponse::success(['availability' => new DoctorAvailabilityResource($availability)], 'Availability slot updated.');
    }

    public function destroy(Request $request, DoctorAvailability $availability)
    {
        Gate::authorize('delete', $availability);

        $this->availabilityService->delete($availability);

        return ApiResponse::success(null, 'Availability slot removed.');
    }
}
