<?php

namespace SmsOtp\Core\Routing;

interface RouteMatcher
{
    public function match(Route $route): bool;
    
    public function path(): string;
    
    public function method(): string;
}
