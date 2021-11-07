<?php

namespace SmsOtp\Controllers;

class RegistrationFormController extends Controller
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
        $input = session()->getFlash('input');
    
        view('register.php', compact([
            'input'
        ]));
    }
}
