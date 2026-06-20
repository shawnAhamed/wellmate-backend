<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnswerRequest;
use App\Http\Resources\AnswerResource;
use App\Models\Answer;
use App\Models\Question;
use App\Services\AnswerService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function __construct(private AnswerService $answerService)
    {
    }

    public function store(StoreAnswerRequest $request, Question $question)
    {
        $doctor = $request->user()->doctor;

        $answer = $this->answerService->store($question, $doctor, $request->validated('body'));

        return ApiResponse::success(['answer' => new AnswerResource($answer)], 'Answer submitted successfully.', 201);
    }

    /**
     * Either the doctor who wrote the answer, or an admin (moderation), may delete it.
     */
    public function destroy(Request $request, Answer $answer)
    {
        $user = $request->user();
        $doctor = $user->doctor;
        $isOwner = $doctor && $answer->doctor_id === $doctor->id;
        $isAdmin = $user->hasRole('admin');

        if (! $isOwner && ! $isAdmin) {
            return ApiResponse::error('You can only delete your own answers.', 403);
        }

        $this->answerService->destroy($answer, $isAdmin && ! $isOwner ? $user : null);

        return ApiResponse::success(null, 'Answer deleted.');
    }
}
