<?php

namespace App\Repositories\Eloquent;

use App\Models\Report;
use App\Models\User;
use App\Repositories\Contracts\ReportRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class EloquentReportRepository extends BaseRepository implements ReportRepositoryInterface
{
    public function __construct(Report $model)
    {
        parent::__construct($model);
    }

    public function existsForUserAndReportable(User $user, Model $reportable): bool
    {
        return $this->model
            ->where('user_id', $user->id)
            ->where('reportable_type', $reportable->getMorphClass())
            ->where('reportable_id', $reportable->getKey())
            ->exists();
    }

    public function paginatedByStatus(?string $status, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with(['user', 'reportable'])
            ->status($status)
            ->latest()
            ->paginate($perPage);
    }
}
