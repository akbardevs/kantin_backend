<?php

namespace App\Services\Users;

use Illuminate\Support\Facades\Validator;
use App\Services\Exceptions\InvalidParameterException;
use App\Services\Exceptions\NotAuthenticatedException;
use App\Services\Exceptions\NotAuthorizedException;
use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Carbon;

class PerformResetPasswordService extends Service
{
    // Reset token will be exipred in 1 day.
    const DEFAULT_TOKEN_EXPIRATION_INTERVAL = 12 * 60 * 60;

    public function __construct(
        array $params,
        array $additionalUpdateParams = [],
        int $tokenExpirationInterval = PerformResetPasswordService::DEFAULT_TOKEN_EXPIRATION_INTERVAL
    ) {
        $this->params = $params;
        $this->additionalUpdateParams = $additionalUpdateParams;
        $this->tokenExpirationInterval = $tokenExpirationInterval;
    }

    public function execute(): User
    {
        $user = $this->fetchUser();
        if (!$user) {
            throw new NotAuthenticatedException("User is not authenticated");
        }

        $elapsedTime = Carbon::now()->diffInSeconds($user->reset_password_requested_at);
        if ($elapsedTime > $this->tokenExpirationInterval) {
            throw new NotAuthorizedException("Token has been expired");
        }

        $updateParams = array_merge($this->additionalUpdateParams, [
            'password' => bcrypt($this->params['new_password']),
            'reset_password_token' => null,
            'reset_password_requested_at' => null,
        ]);

        $user->update($updateParams);

        return $user->refresh();
    }

    private function fetchUser()
    {
        $query = [
            'email' => $this->params['email'],
            'reset_password_token' => $this->params['reset_password_token'],
        ];
        return User::where($query)->first();
    }
}
