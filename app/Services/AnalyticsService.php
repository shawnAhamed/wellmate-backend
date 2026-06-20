<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Doctor;
use App\Models\Question;
use App\Models\User;

class AnalyticsService
{
    public function dashboardStats(): array
    {
        return [
            'total_users' => User::role('user')->count(),
            'total_doctors' => Doctor::count(),
            'verified_doctors' => Doctor::verified()->count(),
            'pending_doctors' => Doctor::pending()->count(),
            'total_questions' => Question::count(),
            'pending_questions' => Question::where('status', 'pending')->count(),
            'answered_questions' => Question::where('status', 'answered')->count(),
            'total_articles' => Article::count(),
            'published_articles' => Article::where('is_published', true)->count(),
        ];
    }
}
