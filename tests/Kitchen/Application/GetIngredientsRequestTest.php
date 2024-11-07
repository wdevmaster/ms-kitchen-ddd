<?php

namespace Tests\Kitchen\Application;

use Kitchen\Application\Helpers\OrderTransformer;
use Kitchen\Application\GetIngredientsRequest;

use Tests\TestCase;

class GetIngredientsRequestTest extends TestCase
{
    public function test_GetIngredientsMultipleDishSuccess()
    {
        $order = (new OrderTransformer())->_encode([
            "id" => "4f55ab26-5fee-4019-9ab6-946a16640ba0",
            "status" => 1,
            "items" => [
                [
                    "dish_id" => "e7ab8733-8e29-4365-9801-9a23e6f2ff66",
                    "order_id" => "4f55ab26-5fee-4019-9ab6-946a16640ba0",
                    "quantity" => 2,
                    "dish" => [
                        "id" => "e7ab8733-8e29-4365-9801-9a23e6f2ff66",
                        "name" => "Pasta chicken",
                        "ingredients" => [
                            [
                            "id" => "7f9e7c22-5ecf-4b17-b1ad-8a34f407ec9d",
                            "name" => "tomato",
                                "pivot" => [
                                    "quantity" => 3,
                                ],
                            ],
                            [
                            "id" => "8874a647-374a-4715-a04e-065916711a5e",
                            "name" => "chicken",
                                "pivot" => [
                                    "quantity" => 4,
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    "dish_id" => "e7ab8733-8e29-4365-9801-9a23e6f2ff66",
                    "order_id" => "4f55ab26-5fee-4019-9ab6-946a16640ba0",
                    "quantity" => 1,
                    "dish" => [
                        "id" => "e7ab8733-8e29-4365-9801-9a23e6f2ff66",
                        "name" => "Pasta meet",
                        "ingredients" => [
                            [
                            "id" => "7f9e7c22-5ecf-4b17-b1ad-8a34f407ec9d",
                            "name" => "tomato",
                                "pivot" => [
                                    "quantity" => 4,
                                ],
                            ],
                            [
                            "id" => "393be228-aea8-4cca-80d5-59f50eec4812",
                            "name" => "meet",
                                "pivot" => [
                                    "quantity" => 2,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $ingredients = [
            [
                "id" => "7f9e7c22-5ecf-4b17-b1ad-8a34f407ec9d",
                "name" => "tomato",
                "quantity" => 10,
            ],
            [
                "id" => "8874a647-374a-4715-a04e-065916711a5e",
                "name" => "chicken",
                "quantity" => 8,
            ],
            [
                "id" => "393be228-aea8-4cca-80d5-59f50eec4812",
                "name" => "meet",
                "quantity" => 2,
            ],
        ];

        $resutl = (new GetIngredientsRequest())->__invoke($order);

        $this->assertEquals($ingredients, $resutl);

    }

    public function test_GetIngredientsOneDishSuccess()
    {
        $order = (new OrderTransformer())->_encode([
            "id" => "4f55ab26-5fee-4019-9ab6-946a16640ba0",
            "status" => 1,
            "items" => [
                [
                    "dish_id" => "e7ab8733-8e29-4365-9801-9a23e6f2ff66",
                    "order_id" => "4f55ab26-5fee-4019-9ab6-946a16640ba0",
                    "quantity" => 1,
                    "dish" => [
                        "id" => "e7ab8733-8e29-4365-9801-9a23e6f2ff66",
                        "name" => "Pasta meet",
                        "ingredients" => [
                            [
                            "id" => "7f9e7c22-5ecf-4b17-b1ad-8a34f407ec9d",
                            "name" => "tomato",
                                "pivot" => [
                                    "quantity" => 4,
                                ],
                            ],
                            [
                            "id" => "393be228-aea8-4cca-80d5-59f50eec4812",
                            "name" => "meet",
                                "pivot" => [
                                    "quantity" => 2,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $ingredients = [
            [
                "id" => "7f9e7c22-5ecf-4b17-b1ad-8a34f407ec9d",
                "name" => "tomato",
                "quantity" => 4,
            ],
            [
                "id" => "393be228-aea8-4cca-80d5-59f50eec4812",
                "name" => "meet",
                "quantity" => 2,
            ],
        ];

        $resutl = (new GetIngredientsRequest())->__invoke($order);

        $this->assertEquals($ingredients, $resutl);

    }
}
