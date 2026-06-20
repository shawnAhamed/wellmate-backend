<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorAvailabilityResource;
use App\Http\Resources\QuestionResource;
use App\Services\DoctorDashboardService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class DoctorDashboardController extends Controller
{
    public function __construct(private DoctorDashboardService $dashboardService)
    {
    }

    public function index(Request $request)
    {
        $dashboard = $this->dashboardService->forDoctor(
            $request->user()->doctor,
            $request->query('category'),
            (int) $request->query('per_page', 10),
        );

        $questionsPayload = QuestionResource::collection($dashboard['pending_questions'])->response()->getData(true);

        return ApiResponse::success([
            'pending_questions' => $questionsPayload['data'],
            'pending_questions_meta' => $questionsPayload['meta'],
            'stats' => $dashboard['stats'],
            'availability' => DoctorAvailabilityResource::collection($dashboard['availability']),
        ]);
    }
}
