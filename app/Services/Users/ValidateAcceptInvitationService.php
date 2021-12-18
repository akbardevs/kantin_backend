<?php

namespace App\Services\Users;

use Illuminate\Support\Facades\Validator;
use App\Services\Exceptions\InvalidParameterException;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserRole;
use App\Services\Service;
use App\Services\ValidateService;

class ValidateAcceptInvitationService extends ValidateService
{
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    protected function getRules(): array {
        return [
            'user.email' => ['required', self::RULE_USER_EMAIL_MAX_LENGTH],
            'user.new_password' => ['required', self::RULE_USER_PASSWORD_MAX_LENGTH],
            'user.reset_password_token' => 'required',
            'question_answers' => 'required',
        ];
    }
}
