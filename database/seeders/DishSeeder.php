<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Kitchen\Infrastructure\Persistence\Models\Dish;
use Kitchen\Infrastructure\Persistence\Models\Ingredient;
use Ramsey\Uuid\Uuid;

class DishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Dish::exists()) {
            $this->command->info('La tabla ya contiene datos. No se insertará nada.');
            return;
        }

        $rows = [
            [
                'name' => 'Meat Curry',
                'ingredients' => ['meat', 'potato', 'rice']
            ],
            [
                'name' => 'Valencian Rice with Chicken',
                'ingredients' => ['rice', 'chicken', 'tomato']
            ],
            [
                'name' => 'Homemade Hamburgers with Salad',
                'ingredients' => ['meat', 'onion', 'potato', 'lettuce', 'tomato', 'cheese', 'ketchup']
            ],
            [
                'name' => 'Potato and Chicken Soup',
                'ingredients' => ['potato', 'chicken']
            ],
            [
                'name' => 'Silbestre Salad',
                'ingredients' => ['lettuce', 'tomato', 'onion', 'lemon']
            ],
            [
                'name' => 'Tomato and Cheese Risotto',
                'ingredients' => ['rice', 'tomato', 'onion', 'cheese']
            ],
        ];

        foreach ($rows as $row) {
            $dish = Dish::create([
                'id' => Uuid::uuid4()->toString(),
                'name' => $row['name'],
            ]);

            foreach ($row['ingredients'] as $ingredientName) {
                $ingredient = Ingredient::where(['name' => $ingredientName])->first();
                $dish->ingredients()->attach($ingredient, ['quantity' => rand(1, 8)]);
            }
        }
    }
}
