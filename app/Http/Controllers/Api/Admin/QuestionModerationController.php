<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use App\Services\QuestionService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class QuestionModerationController extends Controller
{
    public function __construct(private QuestionService $questionService)
    {
    }

    public function close(Request $request, Question $question)
    {
        $question = $this->questionService->close($question, $request->user());

        return ApiResponse::success(['question' => new QuestionResource($question)], 'Question closed.');
    }
}
