<?php

namespace Tests\Kitchen\Application;

use Kitchen\Domain\Entities\Order;
use Kitchen\Domain\ValueObjects\OrderStatus;
use Kitchen\Domain\Repositories\OrderRepository;

use Kitchen\Application\Helpers\OrderTransformer;
use Kitchen\Application\UpdateStatusOrder;

use Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class UpdateStatusOrderTest extends TestCase
{
    private MockObject $orderRepository;
    private UpdateStatusOrder $updateStatusOrder;

    public function setUp(): void
    {
        parent::setUp();

        $this->orderRepository = $this->createMock(OrderRepository::class);

        $this->updateStatusOrder = new UpdateStatusOrder(
            $this->orderRepository
        );
    }

    public function test_UpdateStatusOrderOk()
    {
        $uuid = '6a928af7-9cdd-46b4-ae44-94498e3159d4';
        $order = (new OrderTransformer())->_encode([
            'id' => $uuid,
            'status' => OrderStatus::CREATED,
        ]);

        $this->orderRepository
            ->expects($this->once())
            ->method('findWithoutWith')
            ->willReturnCallback(function ($uuid) use ($order) {
                $order->setStatus(new OrderStatus(OrderStatus::PROCESSED));
                return $order;
            });

        $result = $this->updateStatusOrder->__invoke($order, OrderStatus::PROCESSED);

        $this->assertInstanceOf(Order::class, $result);
        $this->assertEquals(OrderStatus::PROCESSED, $result->getStatus()->getValue());

    }

    public function test_UpdateStatusOrderHandlesInvalidInput()
    {
        $uuid = '6a928af7-9cdd-46b4-ae44-94498e3159d4';
        $order = (new OrderTransformer())->_encode([
            'id' => $uuid,
            'status' => OrderStatus::WAITING,
        ]);

        $this->orderRepository
            ->method('findWithoutWith')
            ->willReturnCallback(function ($uuid) use ($order) {
                $order->setStatus(new OrderStatus(OrderStatus::PROCESSED));
                return $order;
            });

        $result = $this->updateStatusOrder->__invoke($order, OrderStatus::PROCESSED);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid status value');
        $this->updateStatusOrder->__invoke($order, 99);
    }
}
