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
        $logChannel = Log::build([ 'driver' => 'single', 'path' => storage_path('logs/jobs.log')]);


        Log::stack([$logChannel])->debug('GenerateOrderJob::dispatch -------------------------------------------------------------------------');
        Log::stack([$logChannel])->debug('ProcessGenerateOrderService::request', $this->request);
        $processGenerateOrderService->__invoke($this->request);
        Log::stack([$logChannel])->debug('ProcessGenerateOrderService::end -------------------------------------------------------------------');
    }
}
