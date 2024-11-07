<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Kitchen\Infrastructure\Persistence\Models\Ingredient AS KitchenIngredient;
use Ramsey\Uuid\Uuid;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = [
            [
                'id' => '0851cd7d-ca31-46ee-8f08-21f5e4c02636',
                'name' => 'tomato',
            ],
            [
                'id' => '305f2bbe-6fb6-4c4c-9bb7-ee252a84e3b7',
                'name' => 'cheese',
            ],
            [
                'id' => '41db3883-ec32-4f93-8dd0-188bcbb56f16',
                'name' => 'lettuce',
            ],
            [
                'id' => '6237414f-aabb-4efd-885b-bad03ce3f89c',
                'name' => 'rice',
            ],
            [
                'id' => 'a215b23f-f2e7-4f3f-aa17-ca638eb68d83',
                'name' => 'potato',
            ],
            [
                'id' => 'ac43028f-6b7e-4b4d-8687-4b485d300d81',
                'name' => 'lemon',
            ],
            [
                'id' => 'b6c28d62-dad2-458b-84ba-f19c29c6da47',
                'name' => 'meat',
            ],
            [
                'id' => 'bc11a58c-0376-4483-a6f3-459b6d098687',
                'name' => 'chicken',
            ],
            [
                'id' => 'e545db52-be29-44b2-92c1-ca0fc5263a56',
                'name' => 'onion',
            ],
            [
                'id' => 'ff54802f-2b28-44ce-b6ba-c90c69826093',
                'name' => 'ketchup',
            ]
        ];

        foreach ($rows as $row) {
            KitchenIngredient::create($row);
        }
    }
}
