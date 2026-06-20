<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\Doctor;
use App\Models\Question;
use App\Models\User;
use App\Repositories\Contracts\AnswerRepositoryInterface;
use App\Support\AuditLogger;

class AnswerService
{
    public function __construct(
        private AnswerRepositoryInterface $answers,
        private AuditLogger $auditLogger,
    ) {
    }

    /**
     * Answering a pending question is what makes it "active" in the public
     * stream — the question's status flips from pending to answered here.
     */
    public function store(Question $question, Doctor $doctor, string $body): Answer
    {
        $answer = $this->answers->create([
            'question_id' => $question->id,
            'doctor_id' => $doctor->id,
            'body' => $body,
        ]);

        if ($question->status === 'pending') {
            $question->update(['status' => 'answered']);
        }

        return $answer->load('doctor.user');
    }

    public function destroy(Answer $answer, ?User $actingAdmin = null): void
    {
        if ($actingAdmin) {
            $this->auditLogger->log('answer.moderated_delete', $answer, [], [], $actingAdmin->id);
        }

        $this->answers->delete($answer);
    }

    public function acceptAnswer(Question $question, Answer $answer): Answer
    {
        return $this->answers->markAccepted($question, $answer);
    }
}
