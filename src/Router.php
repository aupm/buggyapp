<?php

class Router
{
    public function handle(string $method, string $uri, string $requestId): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);

        // /customers
        if ($uri === '/customers' && $method === 'POST') {
            (new CustomerController($requestId))->store();
            return;
        }

        // /customers/{id}
        if (preg_match('#^/customers/(\d+)$#', $uri, $matches)) {
            $id = (int)$matches[1];

            $controller = new CustomerController($requestId);

            if ($method === 'GET') {
                $controller->show($id);
                return;
            }
        }

        // /orders
        if ($uri === '/orders' && $method === 'POST') {
            (new OrderController($requestId))->store();
            return;
        }

        // /customers/{id}
        if (preg_match('#^/orders/(\d+)$#', $uri, $matches)) {
            $id = (int)$matches[1];

            $controller = new OrderController($requestId);

            if ($method === 'GET') {
                $controller->show($id);
                return;
            }
        }

        http_response_code(404);
        echo json_encode(['data' => null, 'error' => 'Not Found']);
    }
}