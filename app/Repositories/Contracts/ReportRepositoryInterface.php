<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface ReportRepositoryInterface extends RepositoryInterface
{
    public function existsForUserAndReportable(User $user, Model $reportable): bool;

    public function paginatedByStatus(?string $status, int $perPage = 15): LengthAwarePaginator;
}
