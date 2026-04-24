<?php

require_once __DIR__ . '/../src/Router.php';
require_once __DIR__ . '/../src/Controllers/CustomerController.php';
require_once __DIR__ . '/../src/Services/CustomerService.php';
require_once __DIR__ . '/../src/Storage/JsonStorage.php';
require_once __DIR__ . '/../src/Logger.php';

$logger = new Logger(__DIR__ . '/../logs/app.log');

set_exception_handler(function ($e) use ($logger) {
    $logger->error('Unhandled Exception', [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);

    http_response_code(500);
    echo json_encode([
        'data' => null,
        'error' => 'Internal Server Error'
    ]);
});

set_error_handler(function ($severity, $message, $file, $line) use ($logger) {
    $logger->error('PHP Error', compact('severity', 'message', 'file', 'line'));

    throw new ErrorException($message, 0, $severity, $file, $line);
});

header('Content-Type: application/json');

$router = new Router();
$router->handle($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);