<?php

namespace SmsOtp\Core\Log;

class FileLogger implements Logger
{
    /**
     * @param string $path Path where the log should be stored
     */
    public function __construct(
        private string $path
    )
    {
        //
    }
    
    /**
     * @inheritDoc
     */
    public function log(string $message, array $context = []): void
    {
        $chunks = [
            date('[Y-m-d H:i:s]'),
            $message,
            json_encode($context),
            PHP_EOL,
        ];
        
        $fp = fopen($this->path, 'ab+');
        fwrite($fp, implode(' ', $chunks));
        fclose($fp);
    }
    
}
