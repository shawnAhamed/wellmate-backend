<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Question;
use App\Services\VoteService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function __construct(private VoteService $voteService)
    {
    }

    public function voteQuestion(Request $request, Question $question)
    {
        $count = $this->voteService->vote($request->user(), $question);

        return ApiResponse::success(['votes_count' => $count], 'Vote recorded.');
    }

    public function unvoteQuestion(Request $request, Question $question)
    {
        $count = $this->voteService->unvote($request->user(), $question);

        return ApiResponse::success(['votes_count' => $count], 'Vote removed.');
    }

    public function voteAnswer(Request $request, Answer $answer)
    {
        $count = $this->voteService->vote($request->user(), $answer);

        return ApiResponse::success(['votes_count' => $count], 'Vote recorded.');
    }

    public function unvoteAnswer(Request $request, Answer $answer)
    {
        $count = $this->voteService->unvote($request->user(), $answer);

        return ApiResponse::success(['votes_count' => $count], 'Vote removed.');
    }
}
