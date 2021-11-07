<?php

namespace SmsOtp\Core\Routing;

class PathRouteMatcher implements RouteMatcher
{
    /**
     * Path
     *
     * @var string
     */
    private string $path;
    
    /**
     * Method
     *
     * @var string
     */
    private string $method;
    
    /**
     * @param string $path Route path
     * @param string $method Route method
     */
    public function __construct(string $path, string $method)
    {
        $this->path = explode('?', $path)[0] ?? '/';
        $this->method = $method;
    }
    
    /**
     * Check if given route matches
     *
     * @param \SmsOtp\Core\Routing\Route $route
     *
     * @return bool
     */
    public function match(Route $route): bool
    {
        return $this->path === $route->getPath() && $this->method === $route->getMethod();
    }
    
    public function path(): string
    {
        return $this->path;
    }
    
    public function method(): string
    {
        return $this->method;
    }
}
