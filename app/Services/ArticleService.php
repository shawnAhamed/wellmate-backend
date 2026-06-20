<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Doctor;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ArticleService
{
    public function __construct(private ArticleRepositoryInterface $articles)
    {
    }

    public function publicIndex(?string $category, int $perPage = 9): LengthAwarePaginator
    {
        return $this->articles->publishedPaginated($category, $perPage);
    }

    /**
     * Unpublished articles are only visible to their author or an admin.
     */
    public function findVisibleOrFail(Article $article, ?Doctor $viewerDoctor, bool $viewerIsAdmin): Article
    {
        $isOwner = $viewerDoctor && $viewerDoctor->id === $article->doctor_id;

        if (! $article->is_published && ! $viewerIsAdmin && ! $isOwner) {
            throw new ModelNotFoundException;
        }

        return $article->load('doctor.user');
    }

    public function create(Doctor $doctor, array $data): Article
    {
        $article = $this->articles->create([
            ...$data,
            'doctor_id' => $doctor->id,
            'slug' => Article::generateUniqueSlug($data['title']),
        ]);

        return $article->load('doctor.user');
    }

    public function update(Article $article, array $data): Article
    {
        if (isset($data['title']) && $data['title'] !== $article->title) {
            $data['slug'] = Article::generateUniqueSlug($data['title']);
        }

        $this->articles->update($article, $data);

        return $article->load('doctor.user');
    }

    public function delete(Article $article): void
    {
        $this->articles->delete($article);
    }

    public function mine(int $doctorId, int $perPage = 9): LengthAwarePaginator
    {
        return $this->articles->byDoctor($doctorId, $perPage);
    }
}
