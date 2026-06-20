<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\ArticleService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ArticleController extends Controller
{
    public function __construct(private ArticleService $articleService)
    {
    }

    public function index(Request $request)
    {
        $resource = ArticleResource::collection(
            $this->articleService->publicIndex($request->query('category'))
        );

        return ApiResponse::paginated($resource);
    }

    public function show(Request $request, Article $article)
    {
        // Public route (no auth:sanctum middleware) — name the guard explicitly
        // so an authenticated doctor/admin still resolves (see QuestionController::index).
        $viewer = $request->user('sanctum');

        $article = $this->articleService->findVisibleOrFail(
            $article,
            $viewer?->doctor,
            (bool) $viewer?->hasRole('admin')
        );

        return ApiResponse::success(['article' => new ArticleResource($article)]);
    }

    public function store(StoreArticleRequest $request)
    {
        $article = $this->articleService->create($request->user()->doctor, $request->validated());

        return ApiResponse::success(['article' => new ArticleResource($article)], 'Article published successfully.', 201);
    }

    public function update(UpdateArticleRequest $request, Article $article)
    {
        Gate::authorize('update', $article);

        $article = $this->articleService->update($article, $request->validated());

        return ApiResponse::success(['article' => new ArticleResource($article)], 'Article updated successfully.');
    }

    public function destroy(Request $request, Article $article)
    {
        Gate::authorize('delete', $article);

        $this->articleService->delete($article);

        return ApiResponse::success(null, 'Article deleted.');
    }

    /**
     * Articles authored by the currently authenticated doctor (incl. drafts).
     */
    public function mine(Request $request)
    {
        $resource = ArticleResource::collection(
            $this->articleService->mine($request->user()->doctor->id)
        );

        return ApiResponse::paginated($resource);
    }
}
