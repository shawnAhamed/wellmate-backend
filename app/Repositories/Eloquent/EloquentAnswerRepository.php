<?php

namespace App\Repositories\Eloquent;

use App\Models\Answer;
use App\Models\Question;
use App\Repositories\Contracts\AnswerRepositoryInterface;

class EloquentAnswerRepository extends BaseRepository implements AnswerRepositoryInterface
{
    public function __construct(Answer $model)
    {
        parent::__construct($model);
    }

    public function markAccepted(Question $question, Answer $answer): Answer
    {
        $question->answers()->update(['is_accepted' => false]);
        $answer->update(['is_accepted' => true]);

        return $answer;
    }
}
