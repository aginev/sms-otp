<?php

namespace SmsOtp\Core\Routing;

use ArrayIterator;
use SmsOtp\Exceptions\RouterException;

class Router
{
    
    /**
     * @var \ArrayIterator<\SmsOtp\Core\Routing\Route>
     */
    private ArrayIterator $routes;
    
    /**
     * @var \SmsOtp\Core\Routing\RouteMatcher
     */
    private RouteMatcher $matcher;
    
    /**
     * @param \SmsOtp\Core\Routing\RouteMatcher $matcher
     * @param \SmsOtp\Core\Routing\Route[] $routes
     */
    public function __construct(RouteMatcher $matcher, array $routes = [])
    {
        $this->matcher = $matcher;
        $this->routes = new ArrayIterator();
        
        foreach ($routes as $route) {
            $this->add($route);
        }
    }
    
    /**
     * Add new route
     *
     * @param \SmsOtp\Core\Routing\Route $route
     *
     * @return $this
     */
    public function add(Route $route)
    {
        $this->routes->offsetSet($route->uuid(), $route);
        
        return $this;
    }
    
    /**
     * Match current route against the route matcher
     *
     * @return \SmsOtp\Core\Routing\Route
     * @throws \SmsOtp\Exceptions\RouterException
     */
    public function match(): Route
    {
        foreach ($this->routes as $route) {
            if ($this->matcher->match($route)) {
                return $route;
            }
        }
        
        throw RouterException::routeNotFoundException($this->matcher);
    }
    
    /**
     * Get all routes
     *
     * @return \ArrayIterator
     */
    public function getRoutes(): ArrayIterator
    {
        return $this->routes;
    }
    
}
