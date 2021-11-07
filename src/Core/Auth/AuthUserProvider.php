<?php

namespace SmsOtp\Core\Auth;

interface AuthUserProvider
{
    /**
     * Verify credentials
     *
     * @param array $credentials
     *
     * @return bool
     */
    public function verifyCredentials(array $credentials): bool;
    
    /**
     * Retrieve authenticated user
     *
     * @param $identifier
     *
     * @return \SmsOtp\Core\Auth\Authenticatable|null
     */
    public function retrieveAuthenticatedUser($identifier): ?Authenticatable;
}
