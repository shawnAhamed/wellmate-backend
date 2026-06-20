<?php

namespace App\Services;

use App\Models\Question;
use App\Models\User;
use App\Repositories\Contracts\QuestionRepositoryInterface;
use App\Repositories\Contracts\TagRepositoryInterface;
use App\Support\AuditLogger;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class QuestionService
{
    /** Statuses anyone (including guests) can browse in the public stream. */
    private const PUBLIC_STATUSES = ['answered', 'closed'];

    /** Statuses doctors/admins may additionally request, to work their pending queue. */
    private const PRIVILEGED_STATUSES = ['pending', 'answered', 'closed'];

    public function __construct(
        private QuestionRepositoryInterface $questions,
        private TagRepositoryInterface $tags,
        private AuditLogger $auditLogger,
        private SubscriptionService $subscriptions,
    ) {
    }

    /**
     * Pending questions are hidden from the public stream — they only
     * become visible once a doctor answers. Doctors/admins can pass
     * status=pending explicitly to see the unanswered queue.
     */
    public function publicIndex(?User $viewer, ?string $category, ?string $status, int $perPage = 10): LengthAwarePaginator
    {
        $canSeePending = $viewer && ($viewer->hasRole('doctor') || $viewer->hasRole('admin'));

        $statuses = match (true) {
            $status && $canSeePending && in_array($status, self::PRIVILEGED_STATUSES, true) => [$status],
            $status && in_array($status, self::PUBLIC_STATUSES, true) => [$status],
            default => self::PUBLIC_STATUSES,
        };

        return $this->questions->paginatedByStatuses($statuses, $category, $perPage);
    }

    public function myQuestions(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return $this->questions->myQuestions($user->id, $perPage);
    }

    public function findOrFail(int $id): Question
    {
        $question = $this->questions->showWithRelations($id);

        if (! $question) {
            throw new ModelNotFoundException;
        }

        return $question;
    }

    public function create(User $user, array $data): Question
    {
        if (! $this->subscriptions->canAskQuestion($user)) {
            throw ValidationException::withMessages([
                'plan' => ["You've reached your free plan's monthly question limit. Upgrade to ask more."],
            ]);
        }

        $question = $this->questions->create([
            'user_id' => $user->id,
            'title' => $data['title'],
            'body' => $data['body'],
            'category' => $data['category'] ?? 'general',
            'is_anonymous' => $data['is_anonymous'] ?? true,
            'status' => 'pending',
        ]);

        if (! empty($data['tags'])) {
            $tags = $this->tags->findOrCreateMany($data['tags']);
            $question->tags()->sync($tags->pluck('id'));
        }

        return $question->load(['user', 'tags']);
    }

    /**
     * Admin moderation action — closes a question regardless of whether
     * it's been answered (e.g. after reviewing a report).
     */
    public function close(Question $question, User $admin): Question
    {
        $this->questions->update($question, ['status' => 'closed']);

        $this->auditLogger->log('question.closed', $question, [], [], $admin->id);

        return $question;
    }
}
