<?php

namespace SmsOtp\Services\PhoneValidation\PhoneSanitizer;

class PhoneSanitizerFactory
{
    public static function makeFromAlpha2CountryCode(string $code): PhoneSanitizer
    {
        if (mb_strlen($code) !== 2) {
            throw PhoneSanitizerException::invalidCountryCode();
        }
        
        $class = 'SmsOtp\\Services\\PhoneValidation\\PhoneSanitizer\\' . ucfirst(strtolower($code)) . 'PhoneSanitizer';
        
        if (class_exists($class)) {
            return new $class;
        }
    
        throw PhoneSanitizerException::sanitizerNotImplemented($code);
    }
}
