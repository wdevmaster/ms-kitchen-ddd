<?php

namespace Kitchen\Application;

use Kitchen\Domain\ValueObjects\Id;
use Kitchen\Domain\Entities\Order;
use Kitchen\Domain\Entities\OrderItem;
use Kitchen\Domain\ValueObjects\OrderStatus;
use Kitchen\Domain\Repositories\OrderRepository;

use Kitchen\Application\GetRandomDish;

class FindOrder
{
    public function __construct(
        private OrderRepository $orderRepository,
    ){}

    public function __invoke(Array $request): Order
    {
        if (!isset($request['orderId'])) {
            throw new \InvalidArgumentException('Missing required parameters orderId');
        }

        $ordeId = $request['orderId'];
        if (!$order = $this->orderRepository->find($ordeId)) {
            throw new \Exception("Order with ID $ordeId not found");
        }

        return $order;
    }
}
