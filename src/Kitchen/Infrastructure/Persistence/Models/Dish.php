<?php

namespace Kitchen\Infrastructure\Persistence\Models;

use Kitchen\Infrastructure\Persistence\Models\Ingredient;

use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'dish';

    protected $fillable = ['name'];

    protected $casts = [
        'id' => 'string',
    ];

    public function ingredients()
    {
        return $this->belongsToMany(
            Ingredient::class,
            'dish_ingredients'
        )->withPivot('quantity');
    }
}
