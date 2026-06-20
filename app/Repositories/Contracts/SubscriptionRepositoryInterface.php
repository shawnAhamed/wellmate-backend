<?php

namespace App\Repositories\Contracts;

use App\Models\Subscription;

interface SubscriptionRepositoryInterface extends RepositoryInterface
{
    public function currentlyActiveFor(int $userId): ?Subscription;

    public function cancelActiveFor(int $userId): void;
}
