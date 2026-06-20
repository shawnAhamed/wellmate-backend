<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\AuthService;
use App\Support\ApiResponse;

class PasswordResetController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    public function forgot(ForgotPasswordRequest $request)
    {
        $this->authService->sendPasswordResetLink($request->validated('email'));

        return ApiResponse::success(null, 'If that email address is in our system, a password reset link has been sent.');
    }

    public function reset(ResetPasswordRequest $request)
    {
        $this->authService->resetPassword($request->validated());

        return ApiResponse::success(null, 'Password has been reset successfully.');
    }
}
