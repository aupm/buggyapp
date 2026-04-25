<?php

class CustomerService
{
    public function __construct(private readonly JsonStorage $storage) {}

//    public function getAll(): array
//    {
//        return $this->storage->all();
//    }

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
        if (!isset($data['first_name'])) {
            throw new InvalidArgumentException('First name is required');
        }

        $customers = $this->storage->all();

        $newCustomer = [
            'id' => $this->generateId($customers),
            'first_name' => $data['first_name'],
            'last_name' => $data['title'],
        ];

        $customers[] = $newCustomer;
        $this->storage->saveAll($customers);

        return $newCustomer;
    }

//    public function update(int $id, array $data): ?array
//    {
//        $customers = $this->storage->all();
//
//        foreach ($customers as &$customer) {
//            if ($customer['id'] === $id) {
//                $customer['first_name'] = $data['first_name'] ?? $customer['first_name'];
//                $customer['last_name'] = $data['last_name'] ?? $customer['last_name'];
//
//                $this->storage->saveAll($customers);
//                return $customer;
//            }
//        }
//
//        return null;
//    }

//    public function delete(int $id): bool
//    {
//        $customers = $this->storage->all();
//
//        foreach ($customers as $index => $customer) {
//            if ($customer['id'] === $id) {
//                array_splice($customers, $index, 1);
//                $this->storage->saveAll($customers);
//                return true;
//            }
//        }
//
//        return false;
//    }

    private function generateId(array $customers): int
    {
        return empty($customers) ? 1 : max(array_column($customers, 'id')) + 1;
    }
}