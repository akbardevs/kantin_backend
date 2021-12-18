<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use App\Services\Exceptions\InvalidParameterException;

abstract class ValidateService extends Service
{
    const RULE_USER_NAME_MIN_LENGTH = 'min:2';
    const RULE_USER_NAME_MAX_LENGTH = 'max:64';
    const RULE_USER_ID_NO_MAX_LENGTH = 'max:32';
    const RULE_USER_EMAIL_MAX_LENGTH = 'max:64';
    const RULE_USER_USERNAME_MAX_LENGTH = 'max:64';
    const RULE_USER_PASSWORD_MIN_LENGTH = 'min:8';
    const RULE_USER_PASSWORD_MAX_LENGTH = 'max:255';

    protected $params = [];

    public function execute(): array 
    {
        $validator = Validator::make($this->params, $this->getRules());
        if (!$validator->passes()) {
            throw new InvalidParameterException('Data is not valid', $validator->errors()->messages());
        }

        return $validator->validate();
    }

    abstract protected function getRules(): array;
}
