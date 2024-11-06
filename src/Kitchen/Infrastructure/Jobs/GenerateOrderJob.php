<?php

namespace Kitchen\Infrastructure\Jobs;

use Kitchen\Infrastructure\Services\ProcessGenerateOrderService;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class GenerateOrderJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Array $request
    ){}

    /**
     * Execute the job.
     */
    public function handle(ProcessGenerateOrderService $processGenerateOrderService): void
    {
        try {
            $processGenerateOrderService->__invoke($this->request);
        } catch (\Exception $e) {
            $logChannel = Log::build([ 'driver' => 'single', 'path' => storage_path('logs/jobs.log')]);
            Log::stack([$logChannel])->error('GenerateOrderJob => Error processing generate order: ' . $e->getMessage(), ['exception' => $e]);
        }
    }
}
