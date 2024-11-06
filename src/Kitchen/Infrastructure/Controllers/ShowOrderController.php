<?php

namespace Kitchen\Infrastructure\Controllers;

use Kitchen\Domain\Repositories\OrderRepository;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShowOrderController
{
    public function __construct(
        private OrderRepository $orderRepository
    ){}

    public function __invoke(String $uuid): Array
    {
        if (!$row = $this->orderRepository->find($uuid)) {
            throw new ModelNotFoundException();
        }

        return $row->toArray();
    }
}
