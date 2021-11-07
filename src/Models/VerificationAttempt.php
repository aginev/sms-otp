<?php

namespace SmsOtp\Models;

class VerificationAttempt extends Model
{
    
    public $id;
    public $user_id;
    public $code;
    public $created_at;
    
}
