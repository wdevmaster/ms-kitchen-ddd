<?php

namespace Kitchen\Domain\Repositories;

use Kitchen\Domain\Entities\Order;

interface OrderRepository {

    public function find(String $uuid): Order|Null;

    public function findWithoutWith(String $uuid): Order|Null;

    public function save(Order $ingredient): Bool;

}
