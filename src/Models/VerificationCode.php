<?php

namespace SmsOtp\Models;

class VerificationCode extends Model
{
    
    public $id;
    public $user_id;
    public $code;
    public $created_at;
    
}
