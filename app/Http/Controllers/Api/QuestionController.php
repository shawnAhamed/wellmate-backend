<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Resources\AnswerResource;
use App\Http\Resources\QuestionResource;
use App\Models\Answer;
use App\Models\Question;
use App\Services\AnswerService;
use App\Services\QuestionService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class QuestionController extends Controller
{
    public function __construct(
        private QuestionService $questionService,
        private AnswerService $answerService,
    ) {
    }

    public function index(Request $request)
    {
        // This route is public (no auth:sanctum middleware), so the default
        // guard never resolves a user from the Bearer token. Naming the
        // guard explicitly gives doctors/admins their identity while guests
        // still pass through as null — Sanctum's documented optional-auth pattern.
        $resource = QuestionResource::collection($this->questionService->publicIndex(
            $request->user('sanctum'),
            $request->query('category'),
            $request->query('status'),
        ));

        return ApiResponse::paginated($resource);
    }

    /**
     * The authenticated user's own questions, regardless of status —
     * lets the asker see a pending question even though it's hidden
     * from the public stream until a doctor answers it.
     */
    public function mine(Request $request)
    {
        $resource = QuestionResource::collection($this->questionService->myQuestions($request->user()));

        return ApiResponse::paginated($resource);
    }

    public function store(StoreQuestionRequest $request)
    {
        $question = $this->questionService->create($request->user(), $request->validated());

        return ApiResponse::success(['question' => new QuestionResource($question)], 'Question posted successfully.', 201);
    }

    public function show(Question $question)
    {
        $question = $this->questionService->findOrFail($question->id);

        return ApiResponse::success(['question' => new QuestionResource($question)]);
    }

    /**
     * Question owner marks a specific answer as the accepted one.
     */
    public function acceptAnswer(Question $question, Answer $answer)
    {
        Gate::authorize('update', $question);

        if ($answer->question_id !== $question->id) {
            return ApiResponse::error('Answer does not belong to this question.', 422);
        }

        $answer = $this->answerService->acceptAnswer($question, $answer);

        return ApiResponse::success(['answer' => new AnswerResource($answer->load('doctor.user'))], 'Answer marked as accepted.');
    }
}
