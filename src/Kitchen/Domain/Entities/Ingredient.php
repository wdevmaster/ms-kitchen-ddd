<?php

namespace Kitchen\Domain\Entities;

use Kitchen\Domain\ValueObjects\Id;
use Kitchen\Domain\ValueObjects\Quantity;

use Ramsey\Uuid\UuidInterface;

class Ingredient
{
    public function __construct(
        private Id $id,
        protected ?String $name = '',
        protected Quantity $quantity
    ){}

    public static function create(UuidInterface $id, String $name, Int $quantity): self
    {
        return new self( new Id($id), $name,  new Quantity($quantity));
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): String
    {
        return $this->name;
    }

    public function getQty(): Quantity
    {
        return $this->quantity;
    }

    public function toArray(): Array
    {
        return [
            'id' => $this->id->getValue()->toString(),
            'name' => $this->name,
            'quantity' => $this->quantity->getValue(),
        ];
    }
}
