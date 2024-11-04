<?php

namespace Tests\Kitchen\ValueObjects;

use Kitchen\Domain\ValueObjects\Id;
use Tests\TestCase;

use Ramsey\Uuid\Uuid;

class IdTest extends TestCase
{
    public function test_CreateId()
    {
        $uuid = Uuid::uuid4();
        $Id = new Id($uuid);

        $this->assertEquals($uuid->toString(), $Id->getValue()->toString());
    }

    public function test_IdComparison()
    {
        $uuid1 = Uuid::uuid4();
        $uuid2 = Uuid::uuid4();

        $Id1 = new Id($uuid1);
        $Id2 = new Id($uuid2);

        $this->assertTrue($Id1->equals($Id1));
        $this->assertFalse($Id1->equals($Id2));
    }
}
