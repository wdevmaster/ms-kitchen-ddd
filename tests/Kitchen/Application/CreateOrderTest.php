<?php

namespace Tests\Kitchen\Application;

use Kitchen\Domain\Entities\Order;
use Kitchen\Domain\ValueObjects\Id;
use Kitchen\Domain\ValueObjects\OrderStatus;
use Kitchen\Domain\Repositories\OrderRepository;
use Kitchen\Domain\Repositories\DishRepository;

use Kitchen\Application\CreateOrder;
use Kitchen\Application\GetRandomDish;
use Kitchen\Application\Helpers\DishTransformer;

use Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CreateOrderTest extends TestCase
{
    private MockObject $orderRepository;
    private MockObject $dishRepository;
    private CreateOrder $createOrder;

    public function setUp(): void
    {
        parent::setUp();

        $this->orderRepository = $this->createMock(OrderRepository::class);
        $this->dishRepository = $this->createMock(DishRepository::class);

        $this->createOrder = new CreateOrder(
            $this->orderRepository,
            new GetRandomDish(
                $this->dishRepository
            )
        );
    }

    private function setUpDataDishRepository($limit = 1)
    {
        $transform = new DishTransformer();
        $data = [
            $transform->_encode([
                'id' => '44a2255a-d050-4f1b-8d62-f29c1baf9a70',
                'name' => 'Silbestre Salad',
                'ingredients' => [
                    [
                        'id' => '17a36458-77f8-45f9-b0bf-b4bdd27dd658',
                        'name' => 'Tomato Sauce',
                        'pivot' => [
                            'quantity' => 2
                        ]
                    ]
                ]
            ]),
            $transform->_encode([
                'id' => '613b6243-b079-4e81-af0f-8a619477d492',
                'name' => 'Valencian Rice with Chicken',
                'ingredients' => [
                    [
                        'id' => '17a36458-77f8-45f9-b0bf-b4bdd27dd658',
                        'name' => 'Tomato Sauce',
                        'pivot' => [
                            'quantity' => 2
                        ]
                    ]
                ]
            ]),
            $transform->_encode([
                'id' => '94d87b6c-f4b8-41cd-8807-374dcdc92da1',
                'name' => 'Meat Curry',
                'ingredients' => [
                    [
                        'id' => '17a36458-77f8-45f9-b0bf-b4bdd27dd658',
                        'name' => 'Tomato Sauce',
                        'pivot' => [
                            'quantity' => 2
                        ]
                    ]
                ]
            ])
        ];

        $this->dishRepository
            ->expects($this->once())
            ->method('rand')
            ->with($limit)
            ->willReturn($data);

        return $data;
    }

    public function test_CreateOrderSuccess()
    {
        $requestData = [
            'number_dishes' => 1
        ];
        $this->setUpDataDishRepository($requestData['number_dishes']);


        $this->orderRepository
            ->expects($this->once())
            ->method('save')
            ->willReturnCallback(function () {
                return true;
            });

        $result = $this->createOrder->__invoke($requestData);

        $this->assertInstanceOf(Order::class, $result);
        $this->assertEquals(OrderStatus::CREATED, $result->getStatus()->getValue());
    }

    public function test_CreateOrderWithMultipleDishes()
    {
        $requestData = [
            'number_dishes' => 3
        ];

        $data = $this->setUpDataDishRepository($requestData['number_dishes']);

        $this->orderRepository
            ->expects($this->once())
            ->method('save')
            ->willReturnCallback(function () {
                return true;
            });

        $result = $this->createOrder->__invoke($requestData);

        $this->assertInstanceOf(Order::class, $result);
        $this->assertCount($requestData['number_dishes'], $result->getItems());
    }

    public function testCreateOrderWithoutNumberDishes()
    {
        $requestData = [];

        $this->expectException(\Exception::class);
        $this->createOrder->__invoke($requestData);
    }

}
