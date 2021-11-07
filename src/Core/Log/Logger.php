<?php

namespace SmsOtp\Core\Log;

interface Logger
{
    /**
     * Log message
     *
     * @param string $message
     * @param array $context
     */
    public function log(string $message, array $context = []): void;
}
