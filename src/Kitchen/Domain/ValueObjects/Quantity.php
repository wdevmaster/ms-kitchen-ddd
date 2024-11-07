<?php

namespace Kitchen\Domain\ValueObjects;

class Quantity
{
    public function __construct(
        private Int $value
    )
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('Quantity must be positive');
        }

        $this->value = $value;
    }

    public function getValue(): Int
    {
        return intval($this->value);
    }
}
