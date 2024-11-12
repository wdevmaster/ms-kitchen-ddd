<?php

namespace Tests\Kitchen\Infrastructure;

use Kitchen\Domain\ValueObjects\Id;
use Kitchen\Domain\ValueObjects\OrderStatus;
use Kitchen\Domain\Repositories\OrderRepository;
use Kitchen\Domain\Repositories\DishRepository;

use Kitchen\Application\GetIngredientsRequest;
use Kitchen\Application\GetRandomDish;
use Kitchen\Application\UpdateStatusOrder;
use Kitchen\Application\FindOrder;
use Kitchen\Application\CreateOrder;
use Kitchen\Application\OrderControl;
use Kitchen\Application\Helpers\DishTransformer;
use Kitchen\Application\Helpers\OrderTransformer;

use Kitchen\Infrastructure\Services\ProcessGenerateOrderService;

use Aws\Sns\SnsClient;
use Illuminate\Config\Repository;
use App\Services\AWS\Events\EventPublisher;

use PHPUnit\Framework\MockObject\MockObject;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Mockery;
class ProcessGenerateOrderServiceTest extends TestCase
{
    private MockObject $orderRepository;
    private MockObject $dishRepository;
    private MockObject $eventPublisher;
    private ProcessGenerateOrderService $processOrderRequestService;

    public function setUp(): void
    {
        parent::setUp();

        $this->orderRepository = $this->createMock(OrderRepository::class);
        $this->dishRepository = $this->createMock(DishRepository::class);
        $this->eventPublisher = $this->createMock(EventPublisher::class);

        $this->processOrderRequestService = new ProcessGenerateOrderService(
            $this->eventPublisher,
            new OrderControl(
                new FindOrder(
                    $this->orderRepository,

                ),
                new CreateOrder(
                    $this->orderRepository,
                    new GetRandomDish(
                        $this->dishRepository
                    )
                ),
                new UpdateStatusOrder($this->orderRepository)
            ),
            new GetIngredientsRequest()
        );
    }

    private function setUpDataDish()
    {
        $limit = 1;
        $data = [
            [
                "id" => "44a2255a-d050-4f1b-8d62-f29c1baf9a70",
                "name" => "Silbestre Salad",
                "ingredients" => [
                    [
                        "id" => "1dac19de-b82e-4bdc-ae9c-1df102bc650a",
                        "name" => "onion",
                        "pivot" => [
                            "quantity" => 2,
                        ],
                    ],
                    [
                        "id" => "6534eb3a-6b3e-4d72-b6b0-dfa407d258d8",
                        "name" => "lettuce",
                        "pivot" => [
                            "quantity" => 5,
                        ],
                    ],
                    [
                        "id" => "ace314b1-02e8-42b6-a083-e423c2d792fa",
                        "name" => "lemon",
                        "pivot" => [
                            "quantity" => 1,
                        ],
                    ],
                    [
                        "id" => "b4e8e299-fe86-46db-9760-e90d1fd040fb",
                        "name" => "tomato",
                        "pivot" => [
                            "quantity" => 6,
                        ],
                    ],
                ],
            ]
        ];

        $this->dishRepository
            ->expects($this->once())
            ->method('rand')
            ->with($limit)
            ->willReturn(array_map(function($row) {
                return (new DishTransformer())->_encode($row);
            }, $data));

        return $data;
    }

    private function setUpDataOrder($dishes)
    {
        $orderId = Id::create()->getValue()->toString();
        $items = [];
        foreach ($dishes as $dish) {
            $items[] = [
                "dish_id" => $dish['id'],
                "order_id" => $orderId,
                "quantity" => 1,
                "dish" => $dish
            ];
        }

        $order = (new OrderTransformer())->_encode([
            "id" => $orderId,
            "status" => 1,
            "items" => $items
        ]);

        $this->orderRepository
            ->expects($this->once())
            ->method('findWithoutWith')
            ->willReturnCallback(function ($uuid) use ($order) {
                $order->setStatus(new OrderStatus(OrderStatus::PROCESSED));
                return $order;
            });

        $ingredients = (new GetIngredientsRequest())->__invoke($order);

        return [
            'orderId' => $orderId,
            'ingredients' => $ingredients
        ];
    }

    public function test_ProcessOrder()
    {
        $dataDishes = $this->setUpDataDish();
        $this->setUpDataOrder($dataDishes);


        $this->eventPublisher
            ->expects($this->once())
            ->method('publish');

        $this->processOrderRequestService->__invoke([
            'number_dishes' => 1
        ]);
    }
}
