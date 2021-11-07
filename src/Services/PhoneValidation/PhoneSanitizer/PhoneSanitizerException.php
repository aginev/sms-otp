<?php

namespace SmsOtp\Services\PhoneValidation\PhoneSanitizer;

class PhoneSanitizerException extends \Exception
{
    public static function invalidCountryCode()
    {
        return new static('Country code is invalid!', 500);
    }
    
    public static function sanitizerNotImplemented(string $code)
    {
        return new static('Phone sanitizer for ' . $code . ' not implemented!', 500);
    }
    
    public static function invalidPhoneNumber(string $phone)
    {
        return new static('Phone number ' . $phone . ' is not valid!', 422);
    }
}
