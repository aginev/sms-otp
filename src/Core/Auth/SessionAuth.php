<?php

namespace SmsOtp\Core\Auth;

use SmsOtp\Core\Session\Session;

class SessionAuth implements Auth
{
    /**
     * @var \SmsOtp\Core\Auth\Authenticatable
     */
    public static ?Authenticatable $authenticatable = null;
    
    /**
     * @var \SmsOtp\Core\Session\Session
     */
    protected Session $session;
    
    /**
     * @var \SmsOtp\Core\Auth\AuthUserProvider
     */
    protected AuthUserProvider $provider;
    
    public function __construct(
        Session $session,
        AuthUserProvider $userProvider
    )
    {
        $this->session = $session;
        $this->provider = $userProvider;
    }
    
    public function check(): bool
    {
        return $this->user() && $this->session->get($this->sessionKey()) === $this->user()->getAuthIdentifier();
    }
    
    public function guest(): bool
    {
        return !$this->check();
    }
    
    public function user(): ?Authenticatable
    {
        $identifier = $this->session->get($this->sessionKey());
        
        if (!self::$authenticatable) {
            self::$authenticatable = $this->provider->retrieveAuthenticatedUser($identifier);
        }
        
        return self::$authenticatable;
    }
    
    public function id(): int|string|null
    {
        $user = $this->user();
        $identifier = $user->getAuthIdentifierName();
        
        return $user->{$identifier};
    }
    
    public function validate(array $credentials = []): bool
    {
        return $this->provider->verifyCredentials($credentials);
    }
    
    public function setUser(?Authenticatable $user = null): void
    {
        $this->session->set($this->sessionKey(), $user?->getAuthIdentifier());
    }
    
    public function sessionKey(): string
    {
        return 'user_id';
    }
}
