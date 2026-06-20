<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\Article;
use App\Models\Doctor;
use App\Repositories\Contracts\DoctorAvailabilityRepositoryInterface;
use App\Repositories\Contracts\QuestionRepositoryInterface;

class DoctorDashboardService
{
    public function __construct(
        private QuestionRepositoryInterface $questions,
        private DoctorAvailabilityRepositoryInterface $availabilities,
    ) {
    }

    public function forDoctor(Doctor $doctor, ?string $category, int $perPage = 10): array
    {
        return [
            'pending_questions' => $this->questions->paginatedByStatuses(['pending'], $category, $perPage),
            'stats' => [
                'pending_questions_count' => $this->questions->query()->status('pending')->count(),
                'answers_given' => Answer::where('doctor_id', $doctor->id)->count(),
                'accepted_answers' => Answer::where('doctor_id', $doctor->id)->where('is_accepted', true)->count(),
                'published_articles' => Article::where('doctor_id', $doctor->id)->where('is_published', true)->count(),
            ],
            'availability' => $this->availabilities->byDoctor($doctor->id),
        ];
    }
}
