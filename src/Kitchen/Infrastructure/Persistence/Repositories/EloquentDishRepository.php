<?php

namespace Kitchen\Infrastructure\Persistence\Repositories;

use Kitchen\Domain\Entities\Dish;
use Kitchen\Domain\Repositories\DishRepository;

use Kitchen\Application\Helpers\DishTransformer;

use Kitchen\Infrastructure\Persistence\Models\Dish AS Model;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class EloquentDishRepository implements DishRepository
{
    public function all(): Array
    {
        $rows = [];

        return array_map(function($row) {
            return (new DishTransformer())->_encode($row);
        }, $rows);
    }

    public function find(String $uuid): Dish|Null
    {
        try {

        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public function rand(Int $limit = 1): Array|Null
    {
        try {
            $rows = Model::with('ingredients')->inRandomOrder()->limit($limit)->get();

            return array_map(function($row) {
                return (new DishTransformer())->_encode($row);
            }, $rows->toArray());
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }
}
