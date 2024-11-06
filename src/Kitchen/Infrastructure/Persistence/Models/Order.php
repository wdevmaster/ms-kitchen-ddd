<?php

namespace Kitchen\Infrastructure\Persistence\Models;

use Kitchen\Infrastructure\Persistence\Models\OrderItem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'kitchen__orders';

    protected $fillable = [
        'status',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
