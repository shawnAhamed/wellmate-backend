<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use App\Support\ApiResponse;

class DashboardController extends Controller
{
    public function __construct(private AnalyticsService $analyticsService)
    {
    }

    public function stats()
    {
        return ApiResponse::success(['stats' => $this->analyticsService->dashboardStats()]);
    }
}
