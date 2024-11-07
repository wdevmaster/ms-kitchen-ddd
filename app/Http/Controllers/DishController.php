<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kitchen\Infrastructure\Controllers\GetDishController AS GetDishKitchenController;

class DishController extends Controller
{
    public function __construct(
        private GetDishKitchenController $getDishKitchenController,
    ){}

    public function index()
    {
        return response()->json($this->getDishKitchenController->__invoke());
    }
}
