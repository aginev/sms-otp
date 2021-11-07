<?php

namespace SmsOtp\Services\PhoneValidation;

/**
 * Verification code generator
 */
class PhoneVerificationCode
{
    protected string $domain;
    private int $code;
    
    public function __construct(string $domain)
    {
        $this->code = random_int(100000, 999999);
        $this->domain = $domain;
    }
    
    public function code(): int
    {
        return $this->code;
    }
    
    public function message(): string
    {
        return "Your OTP is {$this->code}\n\n@{$this->domain} #{$this->code}";
    }
}
