<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kitchen\Infrastructure\Jobs\GenerateOrderJob;
use Kitchen\Infrastructure\Controllers\GetOrderController AS GetOrderKitchenController;
use Kitchen\Infrastructure\Controllers\ShowOrderController AS ShowKitchenOrderController;

class OrderKitchenController extends Controller
{
    public function __construct(
        private GetOrderKitchenController $getOrderController,
        private ShowKitchenOrderController $showOrderController
    ){}

    public function index(Request $request)
    {
        return response()->json($this->getOrderController->__invoke());
    }

    public function show($uuid)
    {
        return response()->json($this->showOrderController->__invoke($uuid));
    }

    public function store(Request $request)
    {
        $request->validate([
            'number_dishes' => 'required|integer|min:1',
        ]);

        GenerateOrderJob::dispatch($request->all());

        return response()->json(['message' => 'Your order is being processed.'], 201);
    }
}
