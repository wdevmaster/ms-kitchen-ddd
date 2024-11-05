<?php

namespace Kitchen\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'kitchen__ingredients';

    protected $fillable = ['name'];

    protected $casts = [
        'id' => 'string',
    ];

    public function dishes()
    {
        return $this->belongsToMany(
            Dish::class,
            'kitchen__dish_ingredients'
        )->withPivot('quantity');
    }
}
