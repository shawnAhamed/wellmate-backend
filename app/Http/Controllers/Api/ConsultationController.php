<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CancelConsultationRequest;
use App\Http\Requests\StoreConsultationRequest;
use App\Http\Resources\ConsultationResource;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Services\ConsultationService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ConsultationController extends Controller
{
    public function __construct(private ConsultationService $consultationService)
    {
    }

    public function store(StoreConsultationRequest $request)
    {
        $doctor = Doctor::findOrFail($request->validated('doctor_id'));

        $consultation = $this->consultationService->book($request->user(), $doctor, $request->validated());

        return ApiResponse::success(['consultation' => new ConsultationResource($consultation)], 'Consultation requested.', 201);
    }

    public function mine(Request $request)
    {
        $resource = ConsultationResource::collection($this->consultationService->historyForUser($request->user()));

        return ApiResponse::paginated($resource);
    }

    public function doctorIndex(Request $request)
    {
        $resource = ConsultationResource::collection($this->consultationService->historyForDoctor($request->user()->doctor));

        return ApiResponse::paginated($resource);
    }

    public function show(Consultation $consultation)
    {
        Gate::authorize('view', $consultation);

        return ApiResponse::success(['consultation' => new ConsultationResource($consultation->load(['user', 'doctor.user']))]);
    }

    public function confirm(Consultation $consultation)
    {
        Gate::authorize('manage', $consultation);

        $consultation = $this->consultationService->confirm($consultation);

        return ApiResponse::success(['consultation' => new ConsultationResource($consultation)], 'Consultation confirmed.');
    }

    public function complete(Consultation $consultation)
    {
        Gate::authorize('manage', $consultation);

        $consultation = $this->consultationService->complete($consultation);

        return ApiResponse::success(['consultation' => new ConsultationResource($consultation)], 'Consultation marked completed.');
    }

    public function cancel(CancelConsultationRequest $request, Consultation $consultation)
    {
        Gate::authorize('cancel', $consultation);

        $consultation = $this->consultationService->cancel($consultation, $request->user(), $request->validated('reason'));

        return ApiResponse::success(['consultation' => new ConsultationResource($consultation)], 'Consultation cancelled.');
    }
}
