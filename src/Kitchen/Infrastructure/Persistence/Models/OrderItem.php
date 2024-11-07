<?php

namespace Kitchen\Infrastructure\Persistence\Models;

use Kitchen\Infrastructure\Persistence\Models\Order;
use Kitchen\Infrastructure\Persistence\Models\Dish;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $primaryKey = ['dish_id', 'order_id'];
    public $incrementing = false;

    protected $table = 'order_items';

    protected $fillable = [
        'dish_id',
        'order_id',
        'quantity',
    ];

    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
