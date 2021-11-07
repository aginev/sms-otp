<?php

namespace SmsOtp\Core\Session;

interface Session
{
    
    public function start(): void;
    
    public function isStarted(): bool;
    
    public function getId(): string;
    
    public function all(): array;
    
    public function get(string $key, mixed $default = null): mixed;
    
    public function set(string $key, mixed $value): static;
    
    public function forget(string $key): static;
    
    public function flash(string $key, mixed $value);
    
    public function hasFlash(string $key);
    
    public function getFlash(string $key): mixed;
    
    public function has(string $key): bool;
    
    public function exists(string $key): bool;
    
    public function missing(string $key): bool;
    
}
