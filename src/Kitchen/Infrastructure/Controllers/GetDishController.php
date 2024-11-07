<?php

namespace Kitchen\Infrastructure\Controllers;

use Kitchen\Domain\Repositories\DishRepository;

class GetDishController
{
    public function __construct(
        private DishRepository $dishRepository
    ){}

    public function __invoke(): Array
    {
        $rows = $this->dishRepository->all();

        return array_map(function($row){
            return $row->toArray();
        }, $rows);
    }
}
