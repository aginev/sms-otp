<?php

namespace SmsOtp\Services\PhoneValidation\PhoneSanitizer;

abstract class BasePhoneSanitizer
{
    
    public $countryCode;
    
    public function clearSpecialChars(string $phone): string
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }
    
    public function clearLeadingTwoZeros(string $phone): string
    {
        if (str_starts_with($phone, '00')) {
            return substr($phone, 2);
        }
        
        return $phone;
    }
    
    public function addCountryCode(string $phone): string
    {
        if (!str_starts_with($phone, $this->countryCode)) {
            return $this->countryCode . $phone;
        }
        
        return $phone;
    }
}
