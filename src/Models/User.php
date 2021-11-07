<?php

namespace SmsOtp\Models;

use SmsOtp\Core\Auth\Authenticatable;

class User extends Model implements Authenticatable
{
    
    public $id;
    public $email;
    public $phone;
    public $phone_verified_at;
    public $password;
    public $created_at;
    
    /**
     * @inheritDoc
     */
    public function getAuthIdentifierName(): string
    {
        return 'id';
    }
    
    /**
     * @inheritDoc
     */
    public function getAuthIdentifier(): string
    {
        return $this->id;
    }
    
    /**
     * Check if the user has completed his phone verification
     *
     * @return bool
     */
    public function isVerified(): bool
    {
        return (bool)$this->phone_verified_at;
    }
}
