<?php

class CustomerController extends AbstractController
{
    private CustomerService $service;

    public function __construct(protected ?string $requestId = null)
    {
        parent::__construct();
        $storage = new JsonStorage(__DIR__ . '/../../storage/customers.json');
        $this->service = new CustomerService($storage);
    }

//    public function index(): void
//    {
//        $this->respond($this->service->getAll());
//    }

    public function show(int $id): void
    {
        $task = $this->service->getById($id);

        if (!$task) {
            $this->error('Customer not found', 404);
            return;
        }

        $this->respond($task);
    }

    public function store(): void
    {
        $input = $this->getJsonInput();

        if (!isset($input['first_name'])) {
            $this->error('First name is required', 422);
            return;
        }

        $task = $this->service->create($input);
        $this->respond($task, 201);
    }

//    public function update(int $id): void
//    {
//        $input = $this->getJsonInput();
//
//        $task = $this->service->update($id, $input);
//
//        if (!$task) {
//            $this->error('Customer not found', 404);
//            return;
//        }
//
//        $this->respond($task);
//    }

//    public function delete(int $id): void
//    {
//        $deleted = $this->service->delete($id);
//
//        if (!$deleted) {
//            $this->error('Customer not found', 404);
//            return;
//        }
//
//        $this->respond(null, 204);
//    }
}