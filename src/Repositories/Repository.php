<?php

namespace SmsOtp\Repositories;

class Repository
{
    public static function make(): static
    {
        return new static();
    }
    
    protected function connection(): \PDO
    {
        return app()->db();
    }
}
