<?php

namespace SmsOtp\Core;

class Request
{
    const GET = 'GET';
    const HEAD = 'HEAD';
    const POST = 'POST';
    const PUT = 'PUT';
    const PATCH = 'PATCH';
    const DELETE = 'DELETE';
    
    public static function methods()
    {
        return [
            static::GET,
            static::HEAD,
            static::POST,
            static::PUT,
            static::PATCH,
            static::DELETE,
        ];
    }
}
