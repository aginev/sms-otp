<?php

namespace SmsOtp\Core;

use Exception;
use PDO;
use SmsOtp\Core\Auth\Auth;
use SmsOtp\Core\Database\DbConnector;
use SmsOtp\Core\Log\Logger;
use SmsOtp\Core\Log\NullLogger;
use SmsOtp\Core\Routing\Router;
use SmsOtp\Core\Session\Session;
use SmsOtp\Services\Notification\Notification;
use SmsOtp\Services\Notification\NullNotification;

class App
{
    static private $instance;
    
    private PDO $db;
    
    private Router $router;
    
    private Session $session;
    
    private Auth $auth;
    
    private Logger $logger;
    
    private Notification $notification;
    
    private function __construct()
    {
        $this->logger = new NullLogger();
        $this->notification = new NullNotification();
    }
    
    public function __clone()
    {
        throw new Exception('Application cloning not allowed!');
    }
    
    public function __sleep()
    {
        throw new Exception('Application serialization not allowed!');
    }
    
    public function __wakeup()
    {
        throw new Exception('Application deserialization not allowed!');
    }
    
    public static function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new static;
        }
        
        return static::$instance;
    }
    
    public function db(): PDO
    {
        return $this->db;
    }
    
    public function setDb(DbConnector $connector): static
    {
        $this->db = $connector->connect();
        
        return $this;
    }
    
    public function getRouter(): Router
    {
        return $this->router;
    }
    
    public function setRouter(Router $router): static
    {
        $this->router = $router;
        
        return $this;
    }
    
    public function run(): void
    {
        ob_start();
        
        $this->router->match()?->run();
        
        ob_end_flush();
    }
    
    public function getSession(): Session
    {
        return $this->session;
    }
    
    public function setSession(Session $session): static
    {
        $this->session = $session;
        
        return $this;
    }
    
    public function hasSession(): bool
    {
        return (bool)$this->session;
    }
    
    public function getAuth(): Auth
    {
        return $this->auth;
    }
    
    public function setAuth(Auth $auth): static
    {
        $this->auth = $auth;
        
        return $this;
    }
    
    public function getLogger(): Logger
    {
        return $this->logger;
    }
    
    public function setLogger(Logger $logger): static
    {
        $this->logger = $logger;
        
        return $this;
    }
    
    public function getNotification(): Notification
    {
        return $this->notification;
    }
    
    public function setNotification(Notification $notification): static
    {
        $this->notification = $notification;
        
        return $this;
    }
}
