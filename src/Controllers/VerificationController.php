<?php

namespace SmsOtp\Controllers;

use Rakit\Validation\Validator;
use SmsOtp\Exceptions\ValidationException;
use SmsOtp\Repositories\UserRepository;
use SmsOtp\Services\PhoneValidation\PhoneVerification;

class VerificationController extends Controller
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
        
        if (!$phoneVerification->isAllowedToVerify()) {
            redirect()
                ->to('/verify')
                ->go();
        }
        
        try {
            $data = $this->validate($_POST);
        } catch (ValidationException $e) {
            redirect()
                ->to('/verify')
                ->flash('errors', $e->getErrors())
                ->go();
        }
        
        if (!$phoneVerification->verify($data['code'])) {
            redirect()
                ->to('/verify')
                ->flash('errors', [
                    'code' => 'Verification code is invalid!',
                ])
                ->go();
        }
        
        $user = auth()->user();
        
        UserRepository::make()->verifyPhone($user);
        
        $notificationMessage = 'Welcome to SMSBump!';
        
        app()->getNotification()->send($user, $notificationMessage);
        
        redirect()
            ->to('/dashboard')
            ->flash('success', $notificationMessage)
            ->go();
    }
    
    private function validate(array $data): array
    {
        $validator = new Validator();
        $validation = $validator->make($data, [
            'code' => 'required|min:6',
        ]);
        
        $validation->validate();
        
        if ($validation->passes()) {
            return $validation->getValidatedData();
        }
        
        throw new ValidationException($validation->errors()->firstOfAll());
    }
}
