<?php

namespace SmsOtp\Core\Routing;

use SmsOtp\Core\Session\Session;

class Redirect
{
    
    private string $path = '/';
    
    private int $status = 302;
    
    private array $headers = [];
    
    private Session $session;
    
    /**
     * Add header
     *
     * @param string $key
     * @param string $value
     *
     * @return $this
     */
    public function header(string $key, string $value): static
    {
        $this->headers[$key] = $value;
        
        return $this;
    }
    
    /**
     * Add flash message
     *
     * @param string $key
     * @param mixed $value
     *
     * @return $this
     */
    public function flash(string $key, mixed $value): static
    {
        $this->session?->flash($key, $value);
        
        return $this;
    }
    
    /**
     * Set redirect path
     *
     * @param $path
     * @param int $status
     *
     * @return $this
     */
    public function to($path, $status = 302): static
    {
        $this->path = $path;
        $this->status = $status;
        
        return $this;
    }
    
    /**
     * The actual redirect
     */
    public function go()
    {
        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }
        
        header('Location: ' . $this->path, true, $this->status);
        
        exit;
    }
    
    /**
     * @param \SmsOtp\Core\Session\Session $session
     *
     * @return Redirect
     */
    public function setSession(Session $session): Redirect
    {
        $this->session = $session;
        
        return $this;
    }
}
