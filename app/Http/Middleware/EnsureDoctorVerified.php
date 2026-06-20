<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDoctorVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $doctor = $user?->doctor;

        if (! $doctor) {
            return response()->json([
                'message' => 'Only registered doctors can perform this action.',
            ], 403);
        }

        if (! $doctor->is_verified) {
            return response()->json([
                'message' => 'Your doctor account is pending admin verification. You will be able to answer questions and publish articles once verified.',
            ], 403);
        }

        return $next($request);
    }
}
