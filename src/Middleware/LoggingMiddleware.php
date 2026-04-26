<?php

class LoggingMiddleware
{
    public function __construct(private Logger $logger) {}

    public function handle(callable $next): void
    {
        $requestId = uniqid('req_', true);
        $startTime = microtime(true);

        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        $body = file_get_contents('php://input');

        // Log request
        $this->logger->info('Request received', [
            'request_id' => $requestId,
            'method' => $method,
            'uri' => $uri,
            'body' => json_decode($body, true)
        ]);

        // Capture response output
        ob_start();

        try {
            $next($requestId);
        } catch (Throwable $e) {
            $this->logger->error('Unhandled exception in middleware', [
                'request_id' => $requestId,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            http_response_code(500);
            echo json_encode([
                'data' => null,
                'error' => 'Internal Server Error'
            ]);
        }

        $responseBody = ob_get_clean();
        $duration = round((microtime(true) - $startTime) * 1000, 2);

        // Log response
        $this->logger->info('Response sent', [
            'request_id' => $requestId,
            'status' => http_response_code(),
            'duration_ms' => $duration,
            'response' => json_decode($responseBody, true)
        ]);

        echo $responseBody;
    }
}