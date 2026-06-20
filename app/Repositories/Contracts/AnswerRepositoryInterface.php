<?php

namespace App\Repositories\Contracts;

use App\Models\Answer;
use App\Models\Question;

interface AnswerRepositoryInterface extends RepositoryInterface
{
    /**
     * Mark the given answer accepted, unsetting any previously accepted
     * answer on the same question (only one accepted answer at a time).
     */
    public function markAccepted(Question $question, Answer $answer): Answer;
}
