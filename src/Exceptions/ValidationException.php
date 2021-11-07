<?php

namespace SmsOtp\Exceptions;

class ValidationException extends \Exception
{
    private array $errors = [];
    
    public function __construct(array $errors = [])
    {
        parent::__construct('The given data was invalid.', 422);
        
        $this->errors = $errors;
    }
    
    public function getErrors(): array
    {
        return $this->errors;
    }
}
