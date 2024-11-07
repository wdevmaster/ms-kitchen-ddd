<?php

namespace Kitchen\Domain\Entities;

use Kitchen\Domain\ValueObjects\Id;
use Kitchen\Domain\Entities\Ingredient;

class Dish
{
    public function __construct(
        private Id $id,
        private String $name,
        protected Array $ingredients = []
    ){}

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): String
    {
        return $this->name;
    }

    public function getIngredients(): Array
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $item): Void
    {
        $this->ingredients[] = $item;
    }

    public function toArray(): Array
    {
        $ingredients = array_map(function ($items) {
            return $items->toArray();
        }, $this->getIngredients());

        return [
            'id' => $this->getId()->getValue()->toString(),
            'name' => $this->getName(),
            'ingredients' => $ingredients
        ];
    }
}
