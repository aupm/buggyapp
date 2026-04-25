<?php

class OrderService
{
    public function __construct(private readonly JsonStorage $storage) {}

    public function getById(int $id): ?array
    {
        foreach ($this->storage->all() as $customer) {
            if ($customer['id'] === $id) {
                return $customer;
            }
        }

        return null;
    }

    public function create(array $data): array
    {
        if (!isset($data['item_name'])) {
            throw new InvalidArgumentException('Item name is required');
        }

        $customers = $this->storage->all();

        $newCustomer = [
            'id' => $this->generateId($customers),
            'item_name' => $data['item_name'],
            'quantity' => $data['quantity'],
        ];

        $customers[] = $newCustomer;
        $this->storage->saveAll($customers);

        return $newCustomer;
    }

    private function generateId(array $customers): int
    {
        return empty($customers) ? 1 : max(array_column($customers, 'id')) + 1;
    }
}