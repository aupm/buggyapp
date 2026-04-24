<?php

class Logger
{
    private string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function info(string $message, array $context = []): void
    {
        $this->log('INFO', $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->log('ERROR', $message, $context);
    }

    private function log(string $level, string $message, array $context): void
    {
        $entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level' => $level,
            'message' => $message,
            'context' => $context
        ];

        file_put_contents(
            $this->file,
            json_encode($entry) . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }
}