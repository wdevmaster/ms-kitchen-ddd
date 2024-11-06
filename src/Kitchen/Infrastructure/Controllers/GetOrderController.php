<?php

namespace Kitchen\Infrastructure\Controllers;

use Kitchen\Domain\Repositories\OrderRepository;

class GetOrderController
{
    public function __construct(
        private OrderRepository $orderRepository
    ){}

    public function __invoke(): Array
    {
        $rows = $this->orderRepository->all();

        return array_map(function($row){
            return $row->toArray();
        }, $rows);
    }
}
