<?php

namespace SmsOtp\Controllers;

use Rakit\Validation\Validator;
use SmsOtp\Exceptions\ValidationException;
use SmsOtp\Repositories\UserRepository;
use SmsOtp\Services\PhoneValidation\PhoneSanitizer\PhoneSanitizerException;
use SmsOtp\Services\PhoneValidation\PhoneSanitizer\PhoneSanitizerFactory;
use SmsOtp\Services\PhoneValidation\PhoneVerification;

class RegisterController extends Controller
{
    
    public function __construct()
    {
        if (auth()->check()) {
            redirect()
                ->to('/dashboard')
                ->go();
        }
    }
    
    public function __invoke()
    {
        try {
            $data = $this->validate($_POST);
            $data['phone'] = $this->sanitizePhone($data['phone'] ?? null);
        } catch (ValidationException $e) {
            redirect()
                ->to('/register')
                ->flash('errors', $e->getErrors())
                ->flash('input', $_POST)
                ->go();
        }
        
        if (!auth()->validate($data)) {
            redirect()
                ->to('/register')
                ->flash('errors', [
                    'email' => 'This user credentials are not available!',
                ])
                ->flash('input', $_POST)
                ->go();
        }
        
        $user = UserRepository::make()->create($data);
        auth()->setUser($user);
        
        $phoneVerification = new PhoneVerification($user);
        $phoneVerification->send();
        
        redirect()
            ->to('/verify')
            ->flash('success', 'Hello ' . $user->phone . ', we\'ve just sent you and phone verification code!')
            ->go();
    }
    
    private function validate(array $data): array
    {
        $validator = new Validator();
        $validation = $validator->make($data, [
            'email' => 'required|email',
            'phone' => 'required',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);
        
        $validation->validate();
        
        if ($validation->passes()) {
            return $validation->getValidatedData();
        }
        
        throw new ValidationException($validation->errors()->firstOfAll());
    }
    
    private function sanitizePhone(string $phone): string
    {
        try {
            $phoneSanitizer = PhoneSanitizerFactory::makeFromAlpha2CountryCode('BG');
            
            return $phoneSanitizer->sanitize($phone);
        } catch (PhoneSanitizerException $e) {
            throw new ValidationException([
                'phone' => $e->getMessage(),
            ]);
        }
    }
}
