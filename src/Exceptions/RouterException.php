<?php

namespace SmsOtp\Exceptions;

use SmsOtp\Core\Routing\RouteMatcher;

class RouterException extends \Exception
{
    public static function routeNotFoundException(RouteMatcher $matcher)
    {
        return new static('Route [' . $matcher->method() . '] ' . $matcher->path() . ' not found!', 404);
    }
    
    public static function routeNotProperlyDefined()
    {
        return new static('Route not properly defined!', 404);
    }
    
    public static function routeNotCallable()
    {
        return new static('Route controller method or closure can not be called!', 404);
    }
    
    public static function controllerMethodDoesNotExists()
    {
        return new static('Controller method does not exists!', 404);
    }
    
    public static function controllerMethodIsNotAccessable()
    {
        return new static('Controller method should be public!', 404);
    }
}
