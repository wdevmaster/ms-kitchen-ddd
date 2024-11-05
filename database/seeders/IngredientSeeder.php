<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Kitchen\Infrastructure\Persistence\Models\Ingredient;


class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = ['tomato', 'lemon', 'potato', 'rice', 'ketchup', 'lettuce', 'onion', 'cheese', 'meat', 'chicken'];

        foreach ($rows as $row) {
            $uuid = Uuid::uuid4()->toString();

            Ingredient::create([
                'id' => $uuid,
                'name' => $row,
                'quantity' => 5
            ]);
        }
    }
}
