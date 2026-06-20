<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscribeRequest;
use App\Http\Resources\PlanResource;
use App\Http\Resources\SubscriptionResource;
use App\Services\SubscriptionService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(private SubscriptionService $subscriptionService)
    {
    }

    /**
     * No payment gateway is wired up yet — price/billing_interval on the
     * plan are informational only until Stripe (or similar) is added.
     */
    public function mine(Request $request)
    {
        $user = $request->user();
        $plan = $this->subscriptionService->currentPlanFor($user);
        $subscription = $this->subscriptionService->currentSubscriptionFor($user);
        $used = $this->subscriptionService->questionsUsedThisMonth($user);

        return ApiResponse::success([
            'plan' => new PlanResource($plan),
            'subscription' => $subscription ? new SubscriptionResource($subscription) : null,
            'questions_used_this_month' => $used,
            'questions_remaining_this_month' => $plan->monthly_question_limit === null
                ? null
                : max(0, $plan->monthly_question_limit - $used),
        ]);
    }

    public function subscribe(SubscribeRequest $request)
    {
        $subscription = $this->subscriptionService->subscribe($request->user(), $request->validated('plan_slug'));

        return ApiResponse::success(
            ['subscription' => $subscription ? new SubscriptionResource($subscription) : null],
            $subscription ? 'Subscribed successfully.' : 'Reverted to the free plan.'
        );
    }

    public function cancel(Request $request)
    {
        $this->subscriptionService->cancel($request->user());

        return ApiResponse::success(null, 'Subscription cancelled. You are now on the free plan.');
    }
}
