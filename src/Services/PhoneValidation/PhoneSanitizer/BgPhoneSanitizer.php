<?php

namespace SmsOtp\Services\PhoneValidation\PhoneSanitizer;

class BgPhoneSanitizer extends BasePhoneSanitizer implements PhoneSanitizer
{
    
    public $countryCode = 359;
    
    public function validationExpression(): string
    {
        return '/^' . $this->countryCode . '8[7,8,9]{1}\d{7}$/';
    }
    
    public function sanitize(string $input): string
    {
        if (preg_match($this->validationExpression(), $input)) {
            return $input;
        }
        
        $phone = $this->clearSpecialChars($input);
        $phone = $this->clearLeadingTwoZeros($phone);
        $phone = $this->clearLeadingZeroWhenCountryCodeIsMissing($phone);
        $phone = $this->addCountryCode($phone);
        
        if (!preg_match($this->validationExpression(), $phone)) {
            throw PhoneSanitizerException::invalidPhoneNumber($input);
        }
        
        return $phone;
    }
    
    public function clearLeadingZeroWhenCountryCodeIsMissing(string $phone): string
    {
        if (str_starts_with($phone, '00')) {
            return $phone;
        }
        
        if (str_starts_with($phone, $this->countryCode)) {
            return $phone;
        }
        
        if (str_starts_with($phone, '0')) {
            return substr($phone, 1);
        }
        
        return $phone;
    }
}
