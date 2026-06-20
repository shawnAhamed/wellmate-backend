<?php

namespace App\Repositories\Eloquent;

use App\Models\Article;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentArticleRepository extends BaseRepository implements ArticleRepositoryInterface
{
    public function __construct(Article $model)
    {
        parent::__construct($model);
    }

    public function publishedPaginated(?string $category, int $perPage = 9): LengthAwarePaginator
    {
        return $this->model->with('doctor.user')
            ->published()
            ->category($category)
            ->latest()
            ->paginate($perPage);
    }

    public function byDoctor(int $doctorId, int $perPage = 9): LengthAwarePaginator
    {
        return $this->model->where('doctor_id', $doctorId)->latest()->paginate($perPage);
    }
}
