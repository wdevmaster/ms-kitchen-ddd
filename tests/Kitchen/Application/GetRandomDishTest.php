<?php

namespace Tests\Kitchen\Application;

use Kitchen\Domain\Repositories\DishRepository;

use Kitchen\Application\GetRandomDish;

use Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Exception\InvalidUuidStringException;

class GetRandomDishTest extends TestCase
{
    private MockObject $dishRepository;
    private GetRandomDish $getRandomDish;

    public function setUp(): void
    {
        parent::setUp();

        $this->dishRepository = $this->createMock(DishRepository::class);

        $this->getRandomDish = new GetRandomDish(
            $this->dishRepository
        );
    }

    public function test_RandomDishes()
    {
        $limit = 1;
        $data = [
            [
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
            ],
            [
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
            ],
            [
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
            ]
        ];
        $index = rand(0, count($data)-1);

        $dataDB = [$data[$index]];

        $this->dishRepository
            ->expects($this->once())
            ->method('rand')
            ->with($limit)
            ->willReturn($dataDB);

        $result = $this->getRandomDish->__invoke($limit);

        $this->assertCount($limit, $result);
        $this->assertEquals($dataDB, $result);
    }

    public function testNoDishesFound()
    {
        $limit = 3;
        $this->dishRepository
            ->expects($this->once())
            ->method('rand')
            ->with($limit)
            ->willReturn([]);

        $this->expectException(\Exception::class);
        $this->getRandomDish->__invoke($limit);
    }
}
