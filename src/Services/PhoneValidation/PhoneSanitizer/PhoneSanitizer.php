<?php

namespace SmsOtp\Services\PhoneValidation\PhoneSanitizer;

interface PhoneSanitizer
{
    public function validationExpression(): string;
    
    public function sanitize(string $phone): string;
}
