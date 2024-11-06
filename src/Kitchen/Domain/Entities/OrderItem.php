<?php

namespace Kitchen\Domain\Entities;

use Kitchen\Domain\ValueObjects\Quantity;
use Kitchen\Domain\Entities\Dish;

class OrderItem
{
    public function __construct(
        private Dish $dish,
        protected Quantity $quantity
    ) {}

    public static function create(Dish $dish, Int $quantity): self
    {
        return new self($dish, new Quantity($quantity));
    }

    public function getValue(): Dish
    {
        return $this->dish;
    }

    public function getQty(): Quantity
    {
        return $this->quantity;
    }
}
