<?php

abstract class AbstractController
{
    public function __construct(protected ?string $requestId = null) {}
    protected function getJsonInput(): array
    {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }

    protected function respond($data, int $status = 200): void
    {
        http_response_code($status);
        echo json_encode([
            'data' => $data,
            'error' => null
        ]);
    }

    protected function error(string $message, int $status): void
    {
        http_response_code($status);
        echo json_encode([
            'data' => null,
            'error' => $message
        ]);
    }
}