<?php

namespace Tests\Kitchen\ValueObjects;

use Kitchen\Domain\ValueObjects\Quantity;
use Tests\TestCase;


class QuantityTest extends TestCase
{
    public function test_CreateQuantity()
    {
        $value = 3;
        $quantity = new Quantity($value);

        $this->assertEquals($quantity->getValue(), $value);
    }

    public function test_CreateQuantityWithInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Quantity(-3);
    }
}
