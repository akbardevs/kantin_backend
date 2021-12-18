<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use App\Services\Exceptions\InvalidParameterException;
use App\Models\User;
use App\Services\Service;

class SendEmailService extends Service
{
    public function __construct(string $to, string $subject, array $data)
    {
        $this->params = $params;
    }

    public function execute() 
    {
        // TODO
    }
}
