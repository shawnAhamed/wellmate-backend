<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Consultation;
use App\Models\DoctorAvailability;
use App\Models\Question;
use App\Policies\ArticlePolicy;
use App\Policies\ConsultationPolicy;
use App\Policies\DoctorAvailabilityPolicy;
use App\Policies\QuestionPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Question::class, QuestionPolicy::class);
        Gate::policy(Article::class, ArticlePolicy::class);
        Gate::policy(DoctorAvailability::class, DoctorAvailabilityPolicy::class);
        Gate::policy(Consultation::class, ConsultationPolicy::class);

        $this->configureRateLimiting();
        $this->configurePasswordResetUrl();
    }

    /**
     * The Next.js frontend owns the reset form; the email link sends the
     * user there with the token, and the frontend calls POST /api/reset-password.
     */
    private function configurePasswordResetUrl(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return sprintf(
                '%s/reset-password?token=%s&email=%s',
                rtrim(config('app.frontend_url'), '/'),
                $token,
                urlencode($notifiable->getEmailForPasswordReset())
            );
        });
    }

    /**
     * Auth endpoints are limited by IP (pre-login, no user identity yet).
     * Authenticated writes are limited per-user so one abusive account
     * can't exhaust the IP-wide bucket for everyone behind it (NAT/proxy).
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('api-read', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });

        RateLimiter::for('api-write', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });
    }
}
