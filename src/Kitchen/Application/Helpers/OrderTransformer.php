<?php

namespace Kitchen\Application\Helpers;

use Kitchen\Domain\ValueObjects\Id;
use Kitchen\Domain\ValueObjects\OrderStatus;
use Kitchen\Domain\Entities\Order;
use Kitchen\Domain\Entities\OrderItem;
use Kitchen\Application\Helpers\DishTransformer;

use Ramsey\Uuid\Uuid;

class OrderTransformer
{
    public function _encode(Array $params): Order|Array
    {
        if (empty($params)) {
            return [];
        }

        $id = isset($params['id'])
            ? new Id(Uuid::fromString($params['id']))
            : Id::create();

        $status = isset($params['status'])
            ? new OrderStatus($params['status'])
            : new OrderStatus(OrderStatus::CREATED);

        $order = Order::create(
            $id,
            $status,
            isset($params['created_at']) ? $params['created_at'] : null
        );

        $dishTransformer = new DishTransformer();

        if (isset($params['items'])) {
            foreach ($params['items'] as $item) {
                $order->addItem(
                    OrderItem::create(
                        $dishTransformer->_encode($item['dish']),
                        $item['quantity']
                    )
                );
            }
        }

        return $order;
    }
}
