<?php

namespace Kitchen\Application;

use Kitchen\Domain\ValueObjects\Id;
use Kitchen\Domain\Entities\Order;
use Kitchen\Domain\Entities\OrderItem;
use Kitchen\Domain\ValueObjects\OrderStatus;
use Kitchen\Domain\Repositories\OrderRepository;

use Kitchen\Application\GetRandomDish;

class CreateOrder
{
    public function __construct(
        private OrderRepository $orderRepository,
        private GetRandomDish $getRandomDish
    ){}

    public function __invoke(Array $request): Order
    {
        if (!isset($request['number_dishes']) || $request['number_dishes'] < 1) {
            throw new \Exception('number_dishes is required');
        }

        $order = $this->createOrderItemsEntity(
            $this->createOrderEntity(),
            $request['number_dishes']
        );

        $this->orderRepository->save($order);
        return $order;
    }

    private function createOrderEntity(): Order
    {
        return Order::create(
            Id::create(),
            new OrderStatus(OrderStatus::CREATED)
        );
    }

    private function createOrderItemsEntity(Order $order, Int $number_dishes): Order
    {
        $dishes = $this->getRandomDish->__invoke($number_dishes);
        $dishesGrouped = [];

        foreach ($dishes as $dish) {
            $dishId = $dish->getId()->getValue()->toString();

            if (isset($dishesGrouped[$dishId])) {
                $dishesGrouped[$dishId]['quantity'] += 1;
            } else {
                $dishesGrouped[$dishId] = [
                    'quantity' => 1,
                    'dish' => $dish
                ];
            }
        }

        foreach ($dishesGrouped as $dish) {
            $order->addItem(
                OrderItem::create($dish['dish'], $dish['quantity'])
            );
        }

        return $order;
    }
}
