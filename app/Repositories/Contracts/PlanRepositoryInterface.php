<?php

namespace App\Repositories\Contracts;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Collection;

interface PlanRepositoryInterface extends RepositoryInterface
{
    public function activePlans(): Collection;

    public function findBySlug(string $slug): ?Plan;
}
