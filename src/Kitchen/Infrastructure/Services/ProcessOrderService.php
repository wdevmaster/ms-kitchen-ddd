<?php

namespace Kitchen\Infrastructure\Services;

use Kitchen\Domain\ValueObjects\OrderStatus;
use Kitchen\Domain\Entities\Order;

use Kitchen\Application\OrderControl;
use Kitchen\Application\GetIngredientsRequest;

use Illuminate\Support\Facades\Log;

class ProcessOrderService
{
    public function __construct(
        protected OrderControl $orderControl,
        protected GetIngredientsRequest $getIngredientsRequest,
    )
    {}


    public function __invoke(Array $request): Bool
    {
        try {
            $order = $this->orderControl->find($request);
            $this->orderControl->updateStatus($order, OrderStatus::PROCESSED);


            if (!isset($request['ingredients'])) {
                throw new \InvalidArgumentException('Missing required parameters ingredients');
            }

            if (!$this->compareAgainstOrder($order, $request['ingredients'])) {
                throw new \Exception('Order canceld: Insufficient ingredients');
            }

            $this->orderControl->updateStatus($order, OrderStatus::COMPLETED);
            return true;
        } catch (\Exception $e) {
            if ($order) {
                $this->orderControl->updateStatus($order, OrderStatus::CANCELD);
            }

            $logChannel = Log::build([ 'driver' => 'single', 'path' => storage_path('logs/services.log')]);
            Log::stack([$logChannel])->error('ProcessOrderService => Error processing order: ' . $e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    private function compareAgainstOrder(Order $order, Array $ingredientsDispatch): Bool
    {
        $ingredientsOrder = array_column($this->getIngredientsRequest->__invoke($order), 'quantity', 'id');

        foreach ($ingredientsDispatch as $ingredientDispatch) {
            $ingredientId = $ingredientDispatch['id'];
            $dispatchedQuantity = $ingredientDispatch['quantity'];

            if (
                !isset($ingredientsOrder[$ingredientId]) ||
                $ingredientsOrder[$ingredientId] !== $dispatchedQuantity
            ) {
                return false;
            }
        }

        return true;
    }
}
