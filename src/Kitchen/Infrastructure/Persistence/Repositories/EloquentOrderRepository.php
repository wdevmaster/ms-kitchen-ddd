<?php

namespace Kitchen\Infrastructure\Persistence\Repositories;

use Kitchen\Domain\Entities\Order;
use Kitchen\Domain\Repositories\OrderRepository;

use Kitchen\Application\Helpers\OrderTransformer;

use Kitchen\Infrastructure\Persistence\Models\Order AS Model;
use Kitchen\Infrastructure\Persistence\Models\OrderItem;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class EloquentOrderRepository implements OrderRepository
{
    public function all(): Array
    {
        $rows = Model::orderBy('created_at', 'desc')->get();

        return array_map(function($row) {
            return (new OrderTransformer())->_encode($row);
        }, $rows->toArray());
    }

    public function find(String $uuid): Order|Null
    {
        try {
            $model = Model::with('items.dish.ingredients')
            ->findOrFail($uuid);

            return (new OrderTransformer())->_encode($model->toArray());
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public function findWithoutWith(String $uuid): Order|Null
    {
        try {
            $model = Model::findOrFail($uuid);

            return (new OrderTransformer())->_encode($model->toArray());
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public function save(Order $order): Bool
    {
        DB::beginTransaction();
        try {

            $model = Model::with('items')->find(
                $order->getId()->getValue()->toString()
            );

            if (!$model) {
                $model = new Model();
                $model->id = $order->getId()->getValue()->toString();
            }

            $model->status = $order->getStatus()->getValue();
            $model->save();

            $this->syncItemToOrder($model, $order->getItems());

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saving order: ' . $e->getMessage());
            return false;
        }
    }

    private function syncItemToOrder($model, Array $orderItems): Void
    {
        if (!$model->items->isEmpty()) {
            return;
        }

        foreach ($orderItems as $item) {
            $dish = $item->getValue();

            OrderItem::create([
                'order_id' => $model->id,
                'dish_id' => $dish->getId()->getValue()->toString(),
                'quantity' => $item->getQty()->getValue()
            ]);
        }
    }

}
