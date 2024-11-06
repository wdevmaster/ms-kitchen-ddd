<?php

namespace Kitchen\Application;

use Kitchen\Domain\Entities\Order;

class GetIngredientsRequest
{
    public function __invoke(Order $order): Array
    {
        $ingredientsRequest = [];

        foreach ($order->getItems() as $item) {
            $quantityItem = $item->getQty()->getValue();
            foreach ($item->getValue()->getIngredients() as $ingredient) {
                $ingredientId = $ingredient->getId()->getValue()->toString();

                if (!isset($ingredientsRequest[$ingredientId])) {
                    $ingredientsRequest[$ingredientId] = [
                        'id' => $ingredientId,
                        'name' => $ingredient->getName(),
                        'quantity' => $ingredient->getQty()->getValue() * $quantityItem
                    ];
                } else {
                    $ingredientsRequest[$ingredientId]['quantity'] += $ingredient->getQty()->getValue() * $quantityItem;
                }
            }
        }

        return array_values($ingredientsRequest);
    }
}
