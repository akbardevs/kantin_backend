<?php

namespace App\Services\Users;

use Illuminate\Support\Facades\Validator;
use App\Services\Exceptions\InvalidParameterException;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserRole;
use App\Services\Service;
use App\Services\ValidateService;

class ValidateUpdateUserService extends ValidateService
{
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    protected function getRules(): array {
        return [
            'user.name' => ['required', self::RULE_USER_NAME_MIN_LENGTH, self::RULE_USER_NAME_MAX_LENGTH],
            'user.email' => ['required', self::RULE_USER_EMAIL_MAX_LENGTH],
            'user.phone' => 'required|max:16',
            'user.jkel' => 'required',
            'user.village_id' => 'exists:villages,id',
        ];
    }
}
