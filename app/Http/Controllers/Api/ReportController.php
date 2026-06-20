<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Http\Resources\ReportResource;
use App\Models\Answer;
use App\Models\Question;
use App\Services\ReportService;
use App\Support\ApiResponse;

class ReportController extends Controller
{
    public function __construct(private ReportService $reportService)
    {
    }

    public function reportQuestion(StoreReportRequest $request, Question $question)
    {
        $report = $this->reportService->report($request->user(), $question, $request->validated());

        return ApiResponse::success(['report' => new ReportResource($report)], 'Report submitted. Our moderators will review it.', 201);
    }

    public function reportAnswer(StoreReportRequest $request, Answer $answer)
    {
        $report = $this->reportService->report($request->user(), $answer, $request->validated());

        return ApiResponse::success(['report' => new ReportResource($report)], 'Report submitted. Our moderators will review it.', 201);
    }
}
