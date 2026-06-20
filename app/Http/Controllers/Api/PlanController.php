<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Services\SubscriptionService;
use App\Support\ApiResponse;

class PlanController extends Controller
{
    public function __construct(private SubscriptionService $subscriptionService)
    {
    }

    public function index()
    {
        return ApiResponse::success(['plans' => PlanResource::collection($this->subscriptionService->listPlans())]);
    }
}
