<?php

use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\DoctorVerificationController;
use App\Http\Controllers\Api\Admin\QuestionModerationController;
use App\Http\Controllers\Api\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Api\AnswerController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AvailabilityController;
use App\Http\Controllers\Api\ConsultationController;
use App\Http\Controllers\Api\ConsultationMessageController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\DoctorDashboardController;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\VoteController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['message' => 'WellMate API is running']);
});

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/
Route::middleware('throttle:auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [PasswordResetController::class, 'forgot']);
    Route::post('/reset-password', [PasswordResetController::class, 'reset']);
});

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware('signed')
    ->name('verification.verify');

Route::middleware('throttle:api-read')->group(function () {
    // Public index hides pending questions by default — see QuestionService::publicIndex.
    // Authenticated doctors/admins may pass ?status=pending to see the unanswered queue.
    Route::get('/questions', [QuestionController::class, 'index']);
    Route::get('/questions/{question}', [QuestionController::class, 'show']);

    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/{article:slug}', [ArticleController::class, 'show']);

    Route::get('/doctors', [DoctorController::class, 'index']);
    Route::get('/doctors/{doctor}', [DoctorController::class, 'show']);
    Route::get('/doctors/{doctor}/availability', [AvailabilityController::class, 'index']);

    Route::get('/tags', [TagController::class, 'index']);
    Route::get('/plans', [PlanController::class, 'index']);
});

/*
|--------------------------------------------------------------------------
| Authenticated routes (any logged-in user)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:auth');

    // Read-only "my own data" — no email verification required to view it.
    Route::get('/my-questions', [QuestionController::class, 'mine']);
    Route::get('/my-consultations', [ConsultationController::class, 'mine']);
    Route::get('/consultations/{consultation}', [ConsultationController::class, 'show']);
    Route::get('/consultations/{consultation}/messages', [ConsultationMessageController::class, 'index']);
    Route::get('/my-subscription', [SubscriptionController::class, 'mine']);

    /*
    |----------------------------------------------------------------------
    | Write actions require a verified email — kept separate from the
    | 'auth:sanctum' group above so read-only authenticated routes (me,
    | logout, my-questions) stay usable for users who haven't verified yet.
    |----------------------------------------------------------------------
    */
    Route::middleware(['verified', 'throttle:api-write'])->group(function () {
        Route::post('/questions', [QuestionController::class, 'store']);
        Route::patch('/questions/{question}/answers/{answer}/accept', [QuestionController::class, 'acceptAnswer']);

        Route::post('/questions/{question}/votes', [VoteController::class, 'voteQuestion']);
        Route::delete('/questions/{question}/votes', [VoteController::class, 'unvoteQuestion']);
        Route::post('/answers/{answer}/votes', [VoteController::class, 'voteAnswer']);
        Route::delete('/answers/{answer}/votes', [VoteController::class, 'unvoteAnswer']);

        Route::post('/questions/{question}/report', [ReportController::class, 'reportQuestion']);
        Route::post('/answers/{answer}/report', [ReportController::class, 'reportAnswer']);

        Route::post('/consultations', [ConsultationController::class, 'store']);
        Route::patch('/consultations/{consultation}/cancel', [ConsultationController::class, 'cancel']);
        Route::post('/consultations/{consultation}/messages', [ConsultationMessageController::class, 'store']);

        Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
        Route::patch('/subscription/cancel', [SubscriptionController::class, 'cancel']);

        /*
        |------------------------------------------------------------------
        | Answer deletion: doctor-owner OR admin (checked in controller) —
        | kept out of the doctor-only block below so admins aren't blocked
        | by the doctor.verified middleware.
        |------------------------------------------------------------------
        */
        Route::delete('/answers/{answer}', [AnswerController::class, 'destroy']);

        /*
        |------------------------------------------------------------------
        | Doctor-only routes — verified doctors required to answer/publish
        |------------------------------------------------------------------
        */
        Route::middleware(['role:doctor', 'doctor.verified'])->group(function () {
            Route::post('/questions/{question}/answers', [AnswerController::class, 'store']);

            Route::get('/my-articles', [ArticleController::class, 'mine']);
            Route::post('/articles', [ArticleController::class, 'store']);
            Route::put('/articles/{article:slug}', [ArticleController::class, 'update']);
            Route::delete('/articles/{article:slug}', [ArticleController::class, 'destroy']);

            Route::get('/doctor/dashboard', [DoctorDashboardController::class, 'index']);

            Route::get('/my-availability', [AvailabilityController::class, 'mine']);
            Route::post('/my-availability', [AvailabilityController::class, 'store']);
            Route::put('/my-availability/{availability}', [AvailabilityController::class, 'update']);
            Route::delete('/my-availability/{availability}', [AvailabilityController::class, 'destroy']);

            Route::get('/doctor/consultations', [ConsultationController::class, 'doctorIndex']);
            Route::patch('/consultations/{consultation}/confirm', [ConsultationController::class, 'confirm']);
            Route::patch('/consultations/{consultation}/complete', [ConsultationController::class, 'complete']);
        });
    });

    /*
    |----------------------------------------------------------------------
    | Admin-only routes
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

        Route::get('/doctors/pending', [DoctorVerificationController::class, 'pending']);
        Route::get('/doctors/verified', [DoctorVerificationController::class, 'verified']);
        Route::patch('/doctors/{doctor}/verify', [DoctorVerificationController::class, 'verify']);
        Route::delete('/doctors/{doctor}/reject', [DoctorVerificationController::class, 'reject']);

        Route::patch('/questions/{question}/close', [QuestionModerationController::class, 'close']);

        Route::get('/reports', [AdminReportController::class, 'index']);
        Route::patch('/reports/{report}/resolve', [AdminReportController::class, 'resolve']);
        Route::patch('/reports/{report}/dismiss', [AdminReportController::class, 'dismiss']);
    });
});
