<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use App\Services\ReportService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private ReportService $reportService)
    {
    }

    public function index(Request $request)
    {
        $resource = ReportResource::collection(
            $this->reportService->listByStatus($request->query('status', 'pending'))
        );

        return ApiResponse::paginated($resource);
    }

    public function resolve(Request $request, Report $report)
    {
        $report = $this->reportService->resolve($report, $request->user());

        return ApiResponse::success(['report' => new ReportResource($report)], 'Report marked resolved.');
    }

    public function dismiss(Request $request, Report $report)
    {
        $report = $this->reportService->dismiss($report, $request->user());

        return ApiResponse::success(['report' => new ReportResource($report)], 'Report dismissed.');
    }
}
