<?php

namespace SmsOtp\Controllers;

use SmsOtp\Services\PhoneValidation\PhoneVerification;

class VerificationFormController extends Controller
{
    
    public function __construct()
    {
        if (auth()->guest()) {
            redirect()
                ->to('/register')
                ->flash('warning', 'You need to create user account first to be able to access this page!')
                ->go();
        }
    }
    
    public function __invoke()
    {
        $verification = new PhoneVerification(
            auth()->user()
        );
        
        $isAllowedToVerify = $verification->isAllowedToVerify();
        $isAllowedToIssueNewVerificationCode = $verification->isAllowedToIssueNewVerificationCode();
        
        view('verify.php', compact([
            'isAllowedToVerify',
            'isAllowedToIssueNewVerificationCode',
        ]));
    }
}
