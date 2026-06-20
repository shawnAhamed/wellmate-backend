<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\User;
use App\Repositories\Contracts\DoctorRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Support\AuditLogger;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private UserRepositoryInterface $users,
        private DoctorRepositoryInterface $doctors,
        private AuditLogger $auditLogger,
    ) {
    }

    public function register(array $data): array
    {
        $user = $this->users->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'anonymous_handle' => $data['anonymous_handle'] ?? null,
        ]);

        $user->assignRole($data['role']);

        if ($data['role'] === 'doctor') {
            $this->doctors->create([
                'user_id' => $user->id,
                'specialization' => $data['specialization'],
                'bio' => $data['bio'] ?? null,
                'license_number' => $data['license_number'],
                'license_number_hash' => Doctor::hashLicenseNumber($data['license_number']),
                'is_verified' => false,
            ]);
        }

        $user->sendEmailVerificationNotification();

        $token = $user->createToken('auth_token')->plainTextToken;

        $this->auditLogger->log('auth.register', $user, [], ['role' => $data['role']], $user->id);

        return [$user->load('doctor'), $token];
    }

    public function login(array $credentials): array
    {
        $user = $this->users->findByEmail($credentials['email']);

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            $this->auditLogger->log('auth.login_failed', null, [], ['email' => $credentials['email']]);

            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $this->auditLogger->log('auth.login', $user, [], [], $user->id);

        return [$user->load('doctor'), $token];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();

        $this->auditLogger->log('auth.logout', $user, [], [], $user->id);
    }

    /**
     * Always succeeds from the caller's perspective regardless of whether
     * the email exists, so the endpoint can't be used to enumerate accounts.
     */
    public function sendPasswordResetLink(string $email): void
    {
        Password::sendResetLink(['email' => $email]);

        $this->auditLogger->log('auth.password_reset_requested', null, [], ['email' => $email]);
    }

    public function resetPassword(array $data): void
    {
        $status = Password::reset(
            $data,
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
                $user->tokens()->delete();

                $this->auditLogger->log('auth.password_reset_completed', $user, [], [], $user->id);
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }
    }

    public function markEmailVerified(User $user): bool
    {
        if ($user->hasVerifiedEmail()) {
            return false;
        }

        $user->markEmailAsVerified();

        $this->auditLogger->log('auth.email_verified', $user, [], [], $user->id);

        return true;
    }

    public function resendVerificationEmail(User $user): bool
    {
        if ($user->hasVerifiedEmail()) {
            return false;
        }

        $user->sendEmailVerificationNotification();

        return true;
    }
}
