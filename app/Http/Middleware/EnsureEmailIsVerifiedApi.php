<?php

namespace App\Http\Middleware;

use App\Support\ApiResponse;
use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Replaces Laravel's stock "verified" middleware, which redirects to a
 * named "verification.notice" route that doesn't exist in this API-only
 * app (no Accept header would otherwise 500 on the missing route).
 */
class EnsureEmailIsVerifiedApi
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail())) {
            return ApiResponse::error('Your email address is not verified. Please verify your email before performing this action.', 403);
        }

        return $next($request);
    }
}
