<?php

namespace SmsOtp\Exceptions;

class RequestException extends \Exception
{
    public static function methodNotAllowed()
    {
        return new static('Request method not allowed!', 404);
    }
}
