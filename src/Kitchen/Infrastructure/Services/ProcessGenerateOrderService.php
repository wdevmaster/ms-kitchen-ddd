<?php

namespace Kitchen\Infrastructure\Services;

use Kitchen\Domain\ValueObjects\OrderStatus;

use Kitchen\Application\OrderControl;
use Kitchen\Application\GetIngredientsRequest;

use App\Jobs\IngredientsRequest;

class ProcessGenerateOrderService
{
    public function __construct(
        protected OrderControl $orderControl,
        protected GetIngredientsRequest $getIngredientsRequest,
    )
    {}

    public function __invoke(Array $request)
    {
        $order = $this->orderControl->create($request);
        $this->orderControl->updateStatus($order, OrderStatus::WAITING);

        $ingredients = $this->getIngredientsRequest->__invoke($order);

        IngredientsRequest::dispatch([
            'orderId' => $order->getId()->getValue()->toString(),
            'ingredients' => $ingredients
        ])->onQueue('bus-ms');
    }
}
