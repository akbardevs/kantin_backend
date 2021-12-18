<?php

namespace App\Services\Users;

use Illuminate\Support\Facades\Validator;
use App\Services\Exceptions\InvalidParameterException;
use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Carbon;

class RequestResetPasswordService extends Service
{
    public function __construct(array $params, bool $shouldUpdateUser = true)
    {
        $this->params = $params;
        $this->shouldUpdateUser = $shouldUpdateUser;
    }

    public function execute(): string 
    {
        $token = $this->generateResetPasswordToken();
        $user = $this->fetchUser();

        $user->reset_password_token = $token;
        $user->reset_password_requested_at = Carbon::now();

        if ($this->shouldUpdateUser) {
            $user->save();
        }

        return $token;
    }

    private function fetchUser() 
    {
        return array_key_exists('user', $this->params) ? 
            $this->params['user'] : 
            User::find($this->params['user_id']);
    }

    private function generateResetPasswordToken(): string 
    {
        return hash('sha256', uniqid());
    }
}
