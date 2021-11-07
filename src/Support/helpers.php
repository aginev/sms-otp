<?php

use SmsOtp\Core\App;
use SmsOtp\Core\Auth\Auth;
use SmsOtp\Core\Log\Logger;
use SmsOtp\Core\Routing\Redirect;
use SmsOtp\Core\Routing\Router;
use SmsOtp\Core\Session\Session;

if (!function_exists('dump')) {
    /**
     * Dump values
     */
    function dump(...$values): void
    {
        foreach ($values as $value) {
            echo '<pre>';
            var_dump($value);
            echo '</pre>';
        }
    }
}

if (!function_exists('dd')) {
    /**
     * Dump values and die
     */
    function dd(...$values): void
    {
        dump(...$values);
        die();
    }
}

if (!function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @return \SmsOtp\Core\App
     */
    function app(): App
    {
        return App::getInstance();
    }
}

if (!function_exists('router')) {
    /**
     * Get the router instance.
     *
     * @return \SmsOtp\Core\Routing\Router
     */
    function router(): Router
    {
        return app()->getRouter();
    }
}

if (!function_exists('session')) {
    /**
     * Get the session instance.
     *
     * @return \SmsOtp\Core\Session\Session
     */
    function session(): Session
    {
        return app()->getSession();
    }
}

if (!function_exists('auth')) {
    /**
     * Get the auth instance.
     *
     * @return \SmsOtp\Core\Auth\Auth
     */
    function auth(): Auth
    {
        return app()->getAuth();
    }
}

if (!function_exists('logger')) {
    /**
     * Get the logger instance.
     *
     * @return \SmsOtp\Core\Log\Logger
     */
    function logger(): Logger
    {
        return app()->getLogger();
    }
}

if (!function_exists('redirect')) {
    /**
     * Get the redirect instance.
     *
     * @return \SmsOtp\Core\Routing\Redirect
     */
    function redirect(): Redirect
    {
        $redirect = new Redirect();
        
        if (app()->hasSession()) {
            $redirect->setSession(app()->getSession());
        }
        
        return $redirect;
    }
}

if (!function_exists('view')) {
    /**
     * Load view
     */
    function view($path, array $data = []): void
    {
        $fullPath = __DIR__ . '/../Views/' . $path;
        
        if (!file_exists(__DIR__ . '/../Views/' . $path)) {
            throw new \Exception('View ' . $fullPath . ' not found!');
        }
        
        extract($data, EXTR_OVERWRITE);
        
        require_once __DIR__ . '/../Views/' . $path;
    }
}
