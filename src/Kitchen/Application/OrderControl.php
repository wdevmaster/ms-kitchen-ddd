<?php

namespace Kitchen\Application;

use Kitchen\Domain\Entities\Order;

use Kitchen\Application\UpdateStatusOrder;
use Kitchen\Application\CreateOrder;
use Kitchen\Application\FindOrder;

class OrderControl
{
    public function __construct(
        private FindOrder $findOrder,
        private CreateOrder $createOrder,
        private UpdateStatusOrder $updateStatusOrder
    ){}

    public function find(Array $request)
    {
        return $this->findOrder->__invoke($request);
    }

    public function create(Array $request)
    {
        return $this->createOrder->__invoke($request);
    }

    public function updateStatus(Order $order, Int $status): Order
    {
        return $this->updateStatusOrder->__invoke($order, $status);
    }
}
