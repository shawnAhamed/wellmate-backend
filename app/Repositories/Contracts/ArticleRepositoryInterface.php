<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ArticleRepositoryInterface extends RepositoryInterface
{
    public function publishedPaginated(?string $category, int $perPage = 9): LengthAwarePaginator;

    public function byDoctor(int $doctorId, int $perPage = 9): LengthAwarePaginator;
}
