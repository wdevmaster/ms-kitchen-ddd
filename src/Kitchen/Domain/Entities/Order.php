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
        protected ?String $createdAt = null,
        protected Array $items = []
    ){}

    public static function create(Id $id, OrderStatus $status, $createdAt = null): self
    {
        return new self($id, $status, $createdAt);
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

    public function toArray(): Array
    {
        $items = array_map(function ($items) {
            return $items->toArray();
        }, $this->getItems());

        return [
            'id' => $this->getId()->getValue()->toString(),
            'status' => $this->getStatus()->toString(),
            'items' => $items,
            'created_at' => $this->createdAt
        ];

    }
}
