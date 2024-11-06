<?php

namespace Kitchen\Application;

use Kitchen\Domain\Entities\Order;
use Kitchen\Application\UpdateStatusOrder;

use Kitchen\Application\CreateOrder;

class OrderControl
{
    public function __construct(
        private CreateOrder $createOrder,
        private UpdateStatusOrder $updateStatusOrder
    ){}

    public function create(Array $request)
    {
        return $this->createOrder->__invoke($request);
    }

    public function updateStatus(Order $order, Int $status): Order
    {
        return $this->updateStatusOrder->__invoke($order, $status);
    }
}
