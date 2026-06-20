<?php

namespace App\Providers;

use App\Repositories\Contracts\AnswerRepositoryInterface;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use App\Repositories\Contracts\ConsultationMessageRepositoryInterface;
use App\Repositories\Contracts\ConsultationRepositoryInterface;
use App\Repositories\Contracts\DoctorAvailabilityRepositoryInterface;
use App\Repositories\Contracts\DoctorRepositoryInterface;
use App\Repositories\Contracts\PlanRepositoryInterface;
use App\Repositories\Contracts\QuestionRepositoryInterface;
use App\Repositories\Contracts\ReportRepositoryInterface;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;
use App\Repositories\Contracts\TagRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\VoteRepositoryInterface;
use App\Repositories\Eloquent\EloquentAnswerRepository;
use App\Repositories\Eloquent\EloquentArticleRepository;
use App\Repositories\Eloquent\EloquentConsultationMessageRepository;
use App\Repositories\Eloquent\EloquentConsultationRepository;
use App\Repositories\Eloquent\EloquentDoctorAvailabilityRepository;
use App\Repositories\Eloquent\EloquentDoctorRepository;
use App\Repositories\Eloquent\EloquentPlanRepository;
use App\Repositories\Eloquent\EloquentQuestionRepository;
use App\Repositories\Eloquent\EloquentReportRepository;
use App\Repositories\Eloquent\EloquentSubscriptionRepository;
use App\Repositories\Eloquent\EloquentTagRepository;
use App\Repositories\Eloquent\EloquentUserRepository;
use App\Repositories\Eloquent\EloquentVoteRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Map of repository contracts to their Eloquent implementation.
     * New modules register their binding here as they're built.
     */
    public array $bindings = [
        UserRepositoryInterface::class => EloquentUserRepository::class,
        DoctorRepositoryInterface::class => EloquentDoctorRepository::class,
        ArticleRepositoryInterface::class => EloquentArticleRepository::class,
        QuestionRepositoryInterface::class => EloquentQuestionRepository::class,
        AnswerRepositoryInterface::class => EloquentAnswerRepository::class,
        VoteRepositoryInterface::class => EloquentVoteRepository::class,
        ReportRepositoryInterface::class => EloquentReportRepository::class,
        TagRepositoryInterface::class => EloquentTagRepository::class,
        DoctorAvailabilityRepositoryInterface::class => EloquentDoctorAvailabilityRepository::class,
        ConsultationRepositoryInterface::class => EloquentConsultationRepository::class,
        ConsultationMessageRepositoryInterface::class => EloquentConsultationMessageRepository::class,
        PlanRepositoryInterface::class => EloquentPlanRepository::class,
        SubscriptionRepositoryInterface::class => EloquentSubscriptionRepository::class,
    ];
}
