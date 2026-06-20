<?php

namespace App\Repositories\Contracts;

use App\Models\Question;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface QuestionRepositoryInterface extends RepositoryInterface
{
    public function paginatedByStatuses(array $statuses, ?string $category, int $perPage = 10): LengthAwarePaginator;

    public function myQuestions(int $userId, int $perPage = 10): LengthAwarePaginator;

    public function showWithRelations(int $id): ?Question;

    public function countByUserSince(int $userId, \DateTimeInterface $since): int;
}
