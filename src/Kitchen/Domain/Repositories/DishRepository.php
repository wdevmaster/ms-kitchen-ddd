<?php

namespace Kitchen\Domain\Repositories;

use Kitchen\Domain\Entities\Dish;

interface DishRepository {

    public function all(): Array;

    public function find(String $uuid): Dish|Null;

    public function rand(Int $limit = 1): Array;

}
