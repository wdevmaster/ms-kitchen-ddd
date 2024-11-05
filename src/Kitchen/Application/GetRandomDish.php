<?php

namespace Kitchen\Application;

use Kitchen\Domain\Repositories\DishRepository;

class GetRandomDish
{
    public function __construct(
        private DishRepository $dishRepository,
    ){}

    public function __invoke(Int $limit): Array
    {
        $dishes = $this->dishRepository->rand($limit);
        if (empty($dishes)) {
            throw new \Exception("No dishes found");
        }

        return $dishes;
    }
}
