<?php

namespace App\Services\Users;

use Illuminate\Support\Facades\Validator;
use App\Services\Exceptions\InvalidParameterException;
use App\Models\User;
use App\Models\UserRole;
use App\Models\UserGroup;
use App\Services\Service;
use App\Services\ValidateService;
use Illuminate\Support\Str;

class ValidateInviteUserService extends ValidateService
{
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function execute(): array 
    {
        $params =  parent::execute();
        $params['password'] = Str::orderedUuid();

        return $params;
    }

    protected function getRules(): array {
        return [
            'name' => ['required', self::RULE_USER_NAME_MIN_LENGTH, self::RULE_USER_NAME_MAX_LENGTH],
            'identity_number' => [self::RULE_USER_ID_NO_MAX_LENGTH],
            'email' => ['required', self::RULE_USER_EMAIL_MAX_LENGTH],
            'username' => ['required', self::RULE_USER_USERNAME_MAX_LENGTH],
            'age_range' => 'required|max:32',
            'phone' => 'required|max:16',
            'cbo_id' => 'required|max:4',
            'promotor_code' => 'required|max:8',
            'district_id' => 'exists:districts,id',
            'role' => sprintf('required|in:%s', implode(',', [
                UserRole::SUPER_ADMIN,
                UserRole::ADMIN,
                UserRole::USER,
            ])),
            'user_group_id' => 'exists:user_groups,id',
            'affiliate' => 'max:128'
        ];
    }
}
