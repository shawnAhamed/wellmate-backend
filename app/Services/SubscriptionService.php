<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Repositories\Contracts\PlanRepositoryInterface;
use App\Repositories\Contracts\QuestionRepositoryInterface;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;
use App\Support\AuditLogger;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class SubscriptionService
{
    public function __construct(
        private PlanRepositoryInterface $plans,
        private SubscriptionRepositoryInterface $subscriptions,
        private QuestionRepositoryInterface $questions,
        private AuditLogger $auditLogger,
    ) {
    }

    public function listPlans(): Collection
    {
        return $this->plans->activePlans();
    }

    public function currentSubscriptionFor(User $user): ?Subscription
    {
        return $this->subscriptions->currentlyActiveFor($user->id);
    }

    /**
     * No active (paid) subscription means the user is implicitly on the free plan.
     */
    public function currentPlanFor(User $user): Plan
    {
        return $this->currentSubscriptionFor($user)?->plan
            ?? $this->plans->findBySlug('free');
    }

    public function questionsUsedThisMonth(User $user): int
    {
        return $this->questions->countByUserSince($user->id, Carbon::now()->startOfMonth());
    }

    public function canAskQuestion(User $user): bool
    {
        $limit = $this->currentPlanFor($user)->monthly_question_limit;

        return $limit === null || $this->questionsUsedThisMonth($user) < $limit;
    }

    public function canBookConsultation(User $user): bool
    {
        return $this->currentPlanFor($user)->consultation_access;
    }

    /**
     * No payment gateway yet — this activates the plan immediately.
     * Swap in real billing (Stripe checkout/webhook) without changing callers.
     */
    public function subscribe(User $user, string $planSlug): ?Subscription
    {
        $plan = $this->plans->findBySlug($planSlug);

        if (! $plan || ! $plan->is_active) {
            throw ValidationException::withMessages([
                'plan_slug' => ['This plan is not available.'],
            ]);
        }

        $this->subscriptions->cancelActiveFor($user->id);

        if ($plan->slug === 'free') {
            $this->auditLogger->log('subscription.reverted_to_free', null, [], [], $user->id);

            return null;
        }

        $endsAt = match ($plan->billing_interval) {
            'monthly' => Carbon::now()->addMonth(),
            'yearly' => Carbon::now()->addYear(),
            default => null,
        };

        $subscription = $this->subscriptions->create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => Carbon::now(),
            'ends_at' => $endsAt,
        ]);

        $this->auditLogger->log('subscription.created', $subscription, [], ['plan' => $plan->slug], $user->id);

        return $subscription->load('plan');
    }

    public function cancel(User $user): void
    {
        $this->subscriptions->cancelActiveFor($user->id);

        $this->auditLogger->log('subscription.cancelled', null, [], [], $user->id);
    }
}
