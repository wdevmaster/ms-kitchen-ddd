<?php

namespace Kitchen\Application;

use Kitchen\Domain\Entities\Order;
use Kitchen\Domain\ValueObjects\OrderStatus;
use Kitchen\Domain\Repositories\OrderRepository;


class UpdateStatusOrder
{
    public function __construct(
        private OrderRepository $orderRepository
    ){}

    public function __invoke(Order $order, Int $status): Order
    {
        $ordeId = $order->getId()->getValue()->toString();

        if (!$order = $this->orderRepository->findWithoutWith($ordeId)) {
            throw new \Exception("Order with ID $ordeId not found");
        }

        $order->setStatus(new OrderStatus($status));
        $this->orderRepository->save($order);

        return $order;
    }
}
