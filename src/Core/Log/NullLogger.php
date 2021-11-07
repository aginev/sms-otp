<?php

namespace SmsOtp\Core\Log;

class NullLogger implements Logger
{
    
    /**
     * @inheritDoc
     */
    public function log(string $message, array $context = []): void
    {
        //
    }
    
}
