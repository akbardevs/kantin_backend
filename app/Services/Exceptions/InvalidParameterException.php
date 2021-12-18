<?php

namespace App\Services\Exceptions;

class InvalidParameterException extends \Exception
{
    private $errors = [];

    public function __construct(string $message, array $errors = []) {
        parent::__construct($message, 0, null);

        $this->errors = $errors;
    }

    public function __toString(): string {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function getErrors(): array {
        return $this->errors;
    }
}