<?php

namespace Tests\Kitchen\Application;

use Kitchen\Domain\Entities\Order;
use Kitchen\Domain\Entities\Dish;
use Kitchen\Domain\ValueObjects\Id;
use Kitchen\Domain\ValueObjects\Quantity;

use Kitchen\Application\Helpers\OrderTransformer;

use Tests\TestCase;

class OrderTransformerTest extends TestCase
{
    public function test_OrderTransformerEncodeCorrectNumber()
    {
        $requestData = [
            "id" => "4f55ab26-5fee-4019-9ab6-946a16640ba0",
            "status" => 1,
            "items" => [
                [
                    "dish_id" => "e7ab8733-8e29-4365-9801-9a23e6f2ff66",
                    "order_id" => "4f55ab26-5fee-4019-9ab6-946a16640ba0",
                    "quantity" => 1,
                    "dish" => [
                        "id" => "e7ab8733-8e29-4365-9801-9a23e6f2ff66",
                        "name" => "Potato and Chicken Soup",
                        "ingredients" => [
                            [
                            "id" => "7f9e7c22-5ecf-4b17-b1ad-8a34f407ec9d",
                            "name" => "potato",
                                "pivot" => [
                                    "quantity" => 6,
                                ],
                            ],
                            [
                            "id" => "8874a647-374a-4715-a04e-065916711a5e",
                            "name" => "chicken",
                                "pivot" => [
                                    "quantity" => 8,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $order = (new OrderTransformer())->_encode($requestData);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertCount(count($requestData['items']), $order->getItems());

        foreach ($requestData['items'] as $key => $value) {
            $orderItem = $order->getItems()[$key];
            $this->assertEquals($value['quantity'], $orderItem->getQty()->getValue());
            $this->assertInstanceOf(Dish::class, $orderItem->getValue());
        }
    }

    public function test_TransformOrderWithEmptyItems()
    {
        $requestData = [
            "id" => "4f55ab26-5fee-4019-9ab6-946a16640ba0",
            "status" => 1,
            "items" => [],
        ];

        $order = (new OrderTransformer())->_encode($requestData);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertCount(count($requestData['items']), $order->getItems());
    }

    public function test_TransformOrderInvalidId()
    {
        $requestData = [
            "id" => "invalid_uuid",
            "status" => 1,
            "items" => [],
        ];

        $this->expectException(\InvalidArgumentException::class);
        (new OrderTransformer())->_encode($requestData);
    }

    public function test_TransformOrderWithInvalidInput()
    {
        $requestData = [];

        $result = (new OrderTransformer())->_encode($requestData);

        $this->assertEquals([], $result);
    }
}
