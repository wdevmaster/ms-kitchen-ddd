<?php

namespace Kitchen\Domain\ValueObjects;

class OrderStatus
{
    const CREATED   = 1;
    const WAITING   = 2;
    const PROCESSED = 3;
    const COMPLETED = 4;
    const CANCELD   = 0;

    const AVAILABLE_STATUS = [
        self::CREATED   => 'Created',
        self::WAITING   => 'Waiting',
        self::PROCESSED => 'Processed',
        self::COMPLETED => 'Completed',
        self::CANCELD   => 'Canceled',
    ];

    public function __construct(
        private int $value
    ) {
        if ($value < 0 || $value > 4) {
            throw new \InvalidArgumentException('Invalid status value');
        }

        $this->value = $value;
    }

    public function getValue(): Int
    {
        return $this->value;
    }

    public function toString(): String
    {
        return self::AVAILABLE_STATUS[$this->value];
    }
}
