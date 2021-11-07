<?php

namespace SmsOtp\Controllers;

class HomeController extends Controller
{
    public function __invoke()
    {
        view('home.php');
    }
}
