<?php

class OrderController extends AbstractController
{
    private OrderService $service;

    public function __construct(protected ?string $requestId = null)
    {
        parent::__construct();
        $storage = new JsonStorage(__DIR__ . '/../../storage/orders.json');
        $this->service = new OrderService($storage);
    }

    public function show(int $id): void
    {
        $task = $this->service->getById($id);

        if (!$task) {
            $this->error('Order not found', 404);
            return;
        }

        $this->respond($task);
    }

    public function store(): void
    {
        $input = $this->getJsonInput();

        if (!isset($input['item_name'])) {
            $this->error('Item name is required', 422);
            return;
        }

        $task = $this->service->create($input);
        $this->respond($task, 201);
    }
}