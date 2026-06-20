<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    /**
     * Public by design (no auth middleware) — the link is clicked from an
     * email client in a separate, unauthenticated browser context. Security
     * comes from the signed URL (expires + signature) plus the id/hash match,
     * not from a prior session.
     */
    public function verify(Request $request, int $id, string $hash)
    {
        if (! $request->hasValidSignature()) {
            return ApiResponse::error('This verification link is invalid or has expired.', 403);
        }

        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return ApiResponse::error('This verification link is invalid.', 403);
        }

        $verified = $this->authService->markEmailVerified($user);

        return ApiResponse::success(null, $verified ? 'Email verified successfully.' : 'Email already verified.');
    }

    public function resend(Request $request)
    {
        $sent = $this->authService->resendVerificationEmail($request->user());

        return ApiResponse::success(null, $sent ? 'Verification link sent.' : 'Email already verified.');
    }
}
