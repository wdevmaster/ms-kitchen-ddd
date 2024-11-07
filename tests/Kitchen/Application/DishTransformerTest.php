<?php

namespace Tests\Kitchen\Application;

use Kitchen\Domain\Entities\Dish;
use Kitchen\Domain\Entities\Ingredient;

use Kitchen\Application\Helpers\DishTransformer;

use Tests\TestCase;

class DishTransformerTest extends TestCase
{
    public function test_TransformDish()
    {
        $requestData = [
            'id' => '94d87b6c-f4b8-41cd-8807-374dcdc92da1',
            'name' => 'Pizza',
            'ingredients' => [
                [
                    'id' => '17a36458-77f8-45f9-b0bf-b4bdd27dd658',
                    'name' => 'Tomato Sauce',
                    'pivot' => [
                        'quantity' => 2
                    ]
                ],
                [
                    'id' => '8874a647-374a-4715-a04e-065916711a5e',
                    'name' => 'Cheese',
                    'pivot' => [
                        'quantity' => 1
                    ]
                ]
            ]
        ];

        $result = (new DishTransformer())->_encode($requestData);

        $this->assertInstanceOf(Dish::class, $result);
        $this->assertEquals($requestData['id'], $result->getId()->getValue()->toString());
        $this->assertCount(2, $result->getIngredients());

        foreach ($result->getIngredients() as $ingredient) {
            $this->assertInstanceOf(Ingredient::class, $ingredient);
        }
    }

    public function test_TransformDishWithEmptyIngredients()
    {
        $requestData = [
            'id' => '94d87b6c-f4b8-41cd-8807-374dcdc92da1',
            'name' => 'Pizza',
            'ingredients' => []
        ];

        $result = (new DishTransformer())->_encode($requestData);

        $this->assertInstanceOf(Dish::class, $result);
        $this->assertEquals($requestData['id'], $result->getId()->getValue()->toString());
        $this->assertCount(0, $result->getIngredients());
    }

    public function test_TransformDishWithInvalidIngredient()
    {
        $requestData = [
            'id' => 'invalid_uuid',
            'name' => 'Pizza',
            'ingredients' => []
        ];

        $this->expectException(\InvalidArgumentException::class);
        (new DishTransformer())->_encode($requestData);
    }

    public function test_TransformDishWithInvalidInput()
    {
        $requestData = [];

        $result = (new DishTransformer())->_encode($requestData);

        $this->assertEquals([], $result);
    }

}
