<?php

class Router
{
    public function handle(string $method, string $uri): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);

        // /customers
        if ($uri === '/customers' && $method === 'GET') {
            (new CustomerController())->index();
            return;
        }

        if ($uri === '/customers' && $method === 'POST') {
            (new CustomerController())->store();
            return;
        }

        // /customers/{id}
        if (preg_match('#^/customers/(\d+)$#', $uri, $matches)) {
            $id = (int)$matches[1];

            $controller = new CustomerController();

            if ($method === 'GET') {
                $controller->show($id);
                return;
            }

            if ($method === 'PUT') {
                $controller->update($id);
                return;
            }

            if ($method === 'DELETE') {
                $controller->delete($id);
                return;
            }
        }

        http_response_code(404);
        echo json_encode(['data' => null, 'error' => 'Not Found']);
    }
}