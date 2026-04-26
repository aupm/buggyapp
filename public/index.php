<?php

require_once __DIR__ . '/../src/Router.php';
require_once __DIR__ . '/../src/Controllers/AbstractController.php';
require_once __DIR__ . '/../src/Controllers/CustomerController.php';
require_once __DIR__ . '/../src/Services/CustomerService.php';
require_once __DIR__ . '/../src/Controllers/OrderController.php';
require_once __DIR__ . '/../src/Services/OrderService.php';
require_once __DIR__ . '/../src/Storage/JsonStorage.php';
require_once __DIR__ . '/../src/Logger.php';
require_once __DIR__ . '/../src/Middleware/LoggingMiddleware.php';

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

$requestId = uniqid();

$router = new Router();
$middleware = new LoggingMiddleware($logger);

$middleware->handle(function () use ($router, $requestId) {
    $router->handle($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $requestId);
});