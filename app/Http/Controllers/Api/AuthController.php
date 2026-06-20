<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    public function register(RegisterRequest $request)
    {
        [$user, $token] = $this->authService->register($request->validated());

        $message = $user->hasRole('doctor')
            ? 'Registration successful. Your doctor account is pending admin verification. Please check your email to verify your address.'
            : 'Registration successful. Please check your email to verify your address.';

        return ApiResponse::success([
            'user' => new UserResource($user),
            'token' => $token,
        ], $message, 201);
    }

    public function login(LoginRequest $request)
    {
        [$user, $token] = $this->authService->login($request->validated());

        return ApiResponse::success([
            'user' => new UserResource($user),
            'token' => $token,
        ], 'Login successful.');
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return ApiResponse::success(null, 'Logged out successfully.');
    }

    public function me(Request $request)
    {
        $user = $request->user()->load('doctor');

        return ApiResponse::success(['user' => new UserResource($user)]);
    }
}
