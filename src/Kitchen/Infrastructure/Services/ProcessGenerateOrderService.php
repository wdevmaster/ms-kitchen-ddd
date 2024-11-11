<?php

namespace Kitchen\Infrastructure\Services;

use Kitchen\Domain\ValueObjects\OrderStatus;

use Kitchen\Application\OrderControl;
use Kitchen\Application\GetIngredientsRequest;

use App\Services\AWS\Events\EventPublisher;
use Illuminate\Support\Facades\Log;

class ProcessGenerateOrderService
{
    public function __construct(
        private EventPublisher $event,
        protected OrderControl $orderControl,
        protected GetIngredientsRequest $getIngredientsRequest,
    )
    {}

    public function __invoke(Array $request)
    {
        $order = $this->orderControl->create($request);

        try {
            $this->orderControl->updateStatus($order, OrderStatus::WAITING);

            $ingredients = $this->getIngredientsRequest->__invoke($order);

            return $this->event->publish('ingredients.request', [
                'orderId' => $order->getId()->getValue()->toString(),
                'ingredients' => $ingredients
            ]);
        } catch (\Exception $e) {
            $this->orderControl->updateStatus($order, OrderStatus::CANCELD);

            $logChannel = Log::build([ 'driver' => 'single', 'path' => storage_path('logs/services.log')]);
            Log::stack([$logChannel])->error('ProcessGenerateOrderService => Error processing generate order: ' . $e->getMessage(), ['exception' => $e]);
        }
    }
}
