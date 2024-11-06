<?php

namespace Kitchen\Domain\Entities;

use Kitchen\Domain\ValueObjects\Id;
use Kitchen\Domain\ValueObjects\OrderStatus;

use Kitchen\Domain\Entities\OrderItem;

class Order
{
    public function __construct(
        private Id $id,
        protected OrderStatus $status,
        protected Array $items = []
    ){}

    public static function create(Id $id, OrderStatus $status): self
    {
        return new self($id, $status);
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getStatus(): OrderStatus
    {
        return $this->status;
    }

    public function setStatus(OrderStatus $status): Void
    {
        $this->status = $status;
    }

    public function getItems(): Array
    {
        return $this->items;
    }

    public function addItem(OrderItem $item): Void
    {
        $this->items[] = $item;
    }
}
