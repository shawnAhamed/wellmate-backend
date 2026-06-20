<?php

namespace App\Repositories\Eloquent;

use App\Models\Plan;
use App\Repositories\Contracts\PlanRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentPlanRepository extends BaseRepository implements PlanRepositoryInterface
{
    public function __construct(Plan $model)
    {
        parent::__construct($model);
    }

    public function activePlans(): Collection
    {
        return $this->model->active()->orderBy('price')->get();
    }

    public function findBySlug(string $slug): ?Plan
    {
        return $this->model->where('slug', $slug)->first();
    }
}
