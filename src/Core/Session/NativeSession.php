<?php

namespace SmsOtp\Core\Session;

class NativeSession implements Session
{
    
    private array $session;
    
    public function __construct()
    {
        if (!$this->isStarted()) {
            $this->start();
        }
    }
    
    public function start(): void
    {
        if ($this->isStarted()) {
            throw new \RuntimeException('Failed to start the session: already started by PHP.');
        }
        
        if (filter_var(ini_get('session.use_cookies'), FILTER_VALIDATE_BOOLEAN) && headers_sent($file, $line)) {
            throw new \RuntimeException(sprintf('Failed to start the session because headers have already been sent by "%s" at line %d.', $file, $line));
        }
        
        // ok to try and start the session
        if (!session_start()) {
            throw new \RuntimeException('Failed to start the session.');
        }
        
        $this->session = &$_SESSION;
    }
    
    public function isStarted(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }
    
    public function getId(): string
    {
        return session_id();
    }
    
    public function all(): array
    {
        return $this->session;
    }
    
    public function get(string $key, mixed $default = null): mixed
    {
        if (!$this->has($key)) {
            return $default;
        }
        
        return $this->session[$key];
    }
    
    public function set(string $key, mixed $value): static
    {
        $this->session[$key] = $value;
        
        return $this;
    }
    
    public function forget(string $key): static
    {
        if ($this->exists($key)) {
            unset($this->session[$key]);
        }
        
        return $this;
    }
    
    public function flash(string $key, mixed $value)
    {
        $this->session['_flash'][$key] = $value;
    }
    
    public function hasFlash(string $key)
    {
        return $this->has('_flash') && array_key_exists($key, $this->session['_flash']);
    }
    
    public function getFlash(string $key): mixed
    {
        if (!$this->hasFlash($key)) {
            return null;
        }
        
        $value = $this->session['_flash'][$key];
        
        unset($this->session['_flash'][$key]);
        
        return $value;
    }
    
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->session) && $this->session[$key];
    }
    
    public function exists(string $key): bool
    {
        return array_key_exists($key, $this->session);
    }
    
    public function missing(string $key): bool
    {
        return !$this->exists($key);
    }
}
