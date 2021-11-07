<?php

namespace SmsOtp\Core\Auth;

interface Auth
{
    /**
     * Check if the user is logged in
     *
     * @return bool
     */
    public function check(): bool;
    
    /**
     * Check if not logged in
     *
     * @return bool
     */
    public function guest(): bool;
    
    /**
     * Current logged in user
     *
     * @return \SmsOtp\Core\Auth\Authenticatable|null
     */
    public function user(): ?Authenticatable;
    
    /**
     * Current logged in user identifier
     *
     * @return int|string|null
     */
    public function id(): int|string|null;
    
    /**
     * Validate if a user could be created with the credentials specified
     *
     * @param array $credentials
     *
     * @return bool
     */
    public function validate(array $credentials = []): bool;
    
    /**
     * Set user to the storage
     *
     * @param \SmsOtp\Core\Auth\Authenticatable|null $user
     */
    public function setUser(?Authenticatable $user = null): void;
}
