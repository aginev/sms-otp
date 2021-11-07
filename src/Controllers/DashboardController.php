<?php

namespace SmsOtp\Controllers;

class DashboardController extends Controller
{
    public function __construct()
    {
        if (auth()->guest()) {
            redirect()
                ->to('/register')
                ->flash('warning', 'You need to create user account first to be able to access this page!')
                ->go();
        }
        
        if (!auth()->user()?->isVerified()) {
            redirect()
                ->to('/verify')
                ->flash('warning', 'Place verify your phone first!')
                ->go();
        }
    }
    
    public function __invoke()
    {
        view('dashboard.php');
    }
}
