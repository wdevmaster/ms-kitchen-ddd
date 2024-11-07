<?php

namespace Kitchen\Application\Helpers;

use Kitchen\Domain\ValueObjects\Id;
use Kitchen\Domain\Entities\Dish;
use Kitchen\Domain\Entities\Ingredient;

use Ramsey\Uuid\Uuid;

class DishTransformer
{
    public function _encode(Array $params): Dish|Array
    {
        if (empty($params)) {
            return [];
        }

        $id = isset($params['id'])
            ? new Id(Uuid::fromString($params['id']))
            : Id::create();

        $dish = new Dish(
            $id,
            $params['name'],
        );

        foreach ($params['ingredients'] as $ingredient) {
            $dish->addIngredient(
                Ingredient::create(
                    Uuid::fromString($ingredient['id']),
                    $ingredient['name'],
                    $ingredient['pivot']['quantity']
                )
            );
        }

        return $dish;
    }
}
