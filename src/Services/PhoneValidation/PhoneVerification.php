<?php

namespace SmsOtp\Services\PhoneValidation;

use DateTimeImmutable;
use SmsOtp\Models\User;
use SmsOtp\Repositories\VerificationAttemptRepository;
use SmsOtp\Repositories\VerificationCodeRepository;

class PhoneVerification
{
    
    public const MAX_ATTEMPTS_IN_PERIOD = 3;
    
    public const ATTEMPTS_PERIOD = 1 * 60;
    
    public const RESEND_PERIOD = 1 * 60;
    
    public function __construct(
        private User $user
    )
    {
        //
    }
    
    /**
     * Send phone verification message to the user
     */
    public function send(): void
    {
        $verification = new PhoneVerificationCode($_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? $_ENV['APP_DOMAIN']);
        
        VerificationCodeRepository::make()->create($this->user, $verification->code());
        
        app()->getNotification()->send($this->user, $verification->message());
    }
    
    /**
     * Check if the user is allowed to verify his phone number and not throttling
     *
     * @return bool
     */
    public function isAllowedToVerify(): bool
    {
        $attempts = VerificationAttemptRepository::make()->verificationAttempts($this->user);
        
        if (count($attempts) < static::MAX_ATTEMPTS_IN_PERIOD) {
            return true;
        }
        
        $now = new DateTimeImmutable();
        $lastAttempt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $attempts[0]->created_at);
        $secondsSinceLastAttempt = $now->getTimestamp() - $lastAttempt->getTimestamp();
        
        if ($secondsSinceLastAttempt < static::ATTEMPTS_PERIOD) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Check if we can generate and sent brand-new verification code to user
     *
     * @return bool
     */
    public function isAllowedToIssueNewVerificationCode(): bool
    {
        $latestVerificationCode = VerificationCodeRepository::make()->latest($this->user);
        
        if (!$latestVerificationCode) {
            return false;
        }
        
        $now = new DateTimeImmutable();
        $issueDate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $latestVerificationCode->created_at);
        $secondsSinceCodeWasIssued = $now->getTimestamp() - $issueDate->getTimestamp();
        
        if ($secondsSinceCodeWasIssued < static::RESEND_PERIOD) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Verify phone verification code against user input
     *
     * @param $code
     *
     * @return bool
     */
    public function verify($code): bool
    {
        $verificationCode = VerificationCodeRepository::make()->verify($this->user, $code);
        
        if ($verificationCode) {
            return true;
        }
        
        VerificationAttemptRepository::make()->create($this->user, $code);
        
        return false;
    }
}
