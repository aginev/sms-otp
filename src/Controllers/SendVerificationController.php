<?php

namespace SmsOtp\Controllers;

use SmsOtp\Services\PhoneValidation\PhoneVerification;

class SendVerificationController extends Controller
{
    
    public function __construct()
    {
        if (auth()->guest()) {
            redirect()
                ->to('/register')
                ->flash('warning', 'You need to create user account first to be able to access this page!')
                ->go();
        }
        
        if (auth()->user()?->isVerified()) {
            redirect()
                ->to('/dashboard')
                ->flash('warning', 'You\'ve already verified your phone!')
                ->go();
        }
    }
    
    public function __invoke()
    {
        $phoneVerification = new PhoneVerification(auth()->user());
        $phoneVerification->send();
        
        redirect()
            ->to('/verify')
            ->flash('success', 'Your new phone verification code has been sent!')
            ->go();
    }
}
