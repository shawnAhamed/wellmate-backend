<?php

namespace App\Repositories\Eloquent;

use App\Models\Question;
use App\Repositories\Contracts\QuestionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentQuestionRepository extends BaseRepository implements QuestionRepositoryInterface
{
    public function __construct(Question $model)
    {
        parent::__construct($model);
    }

    public function paginatedByStatuses(array $statuses, ?string $category, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with(['user', 'tags'])
            ->withCount(['answers', 'votes'])
            ->statuses($statuses)
            ->category($category)
            ->latest()
            ->paginate($perPage);
    }

    public function myQuestions(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with(['user', 'tags'])
            ->withCount(['answers', 'votes'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    public function showWithRelations(int $id): ?Question
    {
        return $this->model->with(['user', 'tags', 'answers.doctor.user'])
            ->withCount('votes')
            ->find($id);
    }

    public function countByUserSince(int $userId, \DateTimeInterface $since): int
    {
        return $this->model->where('user_id', $userId)
            ->where('created_at', '>=', $since)
            ->count();
    }
}
