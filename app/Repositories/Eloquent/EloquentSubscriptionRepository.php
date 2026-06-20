<?php

namespace App\Repositories\Eloquent;

use App\Models\Subscription;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;

class EloquentSubscriptionRepository extends BaseRepository implements SubscriptionRepositoryInterface
{
    public function __construct(Subscription $model)
    {
        parent::__construct($model);
    }

    public function currentlyActiveFor(int $userId): ?Subscription
    {
        return $this->model->with('plan')
            ->where('user_id', $userId)
            ->currentlyActive()
            ->latest('starts_at')
            ->first();
    }

    public function cancelActiveFor(int $userId): void
    {
        $this->model->where('user_id', $userId)
            ->currentlyActive()
            ->update(['status' => 'cancelled']);
    }
}
