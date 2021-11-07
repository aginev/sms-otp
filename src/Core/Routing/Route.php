<?php

namespace SmsOtp\Core\Routing;

use Closure;
use ReflectionMethod;
use SmsOtp\Core\Request;
use SmsOtp\Exceptions\RequestException;
use SmsOtp\Exceptions\RouterException;

class Route
{
    
    private string $invocableControllersMethodName = '__invoke';
    
    private string $method;
    
    private string $path;
    
    private string|array|Closure $callable;
    
    private string $name = '';
    
    public function __construct(
        string $method,
        string $path,
        string|array|Closure $callable
    )
    {
        if (!in_array($method, Request::methods())) {
            throw RequestException::methodNotAllowed();
        }
        
        $this->method = $method;
        $this->path = $path;
        $this->callable = $callable;
    }
    
    public static function make(
        string $method,
        string $path,
        string|array|Closure $callable
    ): static
    {
        return new static($method, $path, $callable);
    }
    
    public static function get(
        string $path,
        array|string|callable $callable
    ): static
    {
        return new static(Request::GET, $path, $callable);
    }
    
    public static function head(
        string $path,
        array|string|callable $callable
    ): static
    {
        return new static(Request::HEAD, $path, $callable);
    }
    
    public static function post(
        string $path,
        string|array|Closure $callable
    ): static
    {
        return new static(Request::POST, $path, $callable);
    }
    
    public static function put(
        string $path,
        string|array|Closure $callable
    ): static
    {
        return new static(Request::PUT, $path, $callable);
    }
    
    public static function patch(
        string $path,
        string|array|Closure $callable
    ): static
    {
        return new static(Request::PATCH, $path, $callable);
    }
    
    public static function delete(
        string $path,
        string|array|Closure $callable
    ): static
    {
        return new static(Request::DELETE, $path, $callable);
    }
    
    public function name(string $name)
    {
        $this->name = $name;
        
        return $this;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function getMethod(): string
    {
        return $this->method;
    }
    
    public function getPath(): string
    {
        return '/' . rtrim(ltrim(trim($this->path), '/'), '/');
    }
    
    public function uuid(): string
    {
        return "[" . $this->getMethod() . "] " . $this->getPath();
    }
    
    public function run()
    {
        if ($this->callable instanceof Closure) {
            ($this->callable)();
            
            return;
        }
        
        $callable = is_string($this->callable)
            ? [$this->callable, $this->invocableControllersMethodName]
            : $this->callable;
        
        if (!(new \ReflectionClass($callable[0]))->hasMethod($callable[1])) {
            throw RouterException::controllerMethodDoesNotExists();
        }
        
        if (!(new ReflectionMethod($callable[0], $callable[1]))->isPublic()) {
            throw RouterException::controllerMethodIsNotAccessable();
        }
        
        if (!is_callable($callable, true)) {
            throw RouterException::routeNotCallable();
        }
        
        $class = new ($callable[0]);
        
        $class->{$callable[1]}();
    }
    
}
