<?php

namespace Kitchen\Domain\ValueObjects;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Id
{
    public function __construct(
        private UuidInterface $value
    ){}

    public static function create(): self
    {
        return new self(Uuid::uuid4());
    }

    public function getValue(): UuidInterface
    {
        return $this->value;
    }

    public function equals(Id $other): bool
    {
        return $this->value->equals($other->value);
    }
}
