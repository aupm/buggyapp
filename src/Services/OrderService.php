<?php

class OrderService
{
    public function __construct(private readonly JsonStorage $storage) {}

    public function getById(int $id): ?array
    {

        return [];

        foreach ($this->storage->all() as $order) {
            if ($order['id'] === $id) {
                return $order;
            }
        }

        return null;
    }

    public function create(array $data): array
    {
        if (!isset($data['item_name'])) {
            throw new InvalidArgumentException('Item name is required');
        }

        $orders = $this->storage->all();

        $newCustomer = [
            'id' => $this->generateId($orders),
            'item_name' => $data['item_name'],
            'quantity' => $data['quantity'],
        ];

        $orders[] = $newCustomer;
        $this->storage->saveAll($orders);

        return $newCustomer;
    }

    private function generateId(array $orders): int
    {
        return empty($orders) ? 1 : max(array_column($orders, 'id')) + 1;
    }
}