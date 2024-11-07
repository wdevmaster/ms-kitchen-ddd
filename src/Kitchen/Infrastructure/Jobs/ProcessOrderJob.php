<?php

namespace Kitchen\Infrastructure\Jobs;

use Kitchen\Infrastructure\Services\ProcessOrderService;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessOrderJob implements ShouldQueue
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
    public function handle(ProcessOrderService $processOrderService): void
    {
        $logChannel = Log::build([ 'driver' => 'single', 'path' => storage_path('logs/jobs.log')]);

        Log::stack([$logChannel])->debug('ProcessOrderJob::dispatch -------------------------------------------------------------------------');
        Log::stack([$logChannel])->debug('ProcessOrderService::request', $this->request);
        $processOrderService->__invoke($this->request);
        Log::stack([$logChannel])->debug('ProcessOrderService::end --------------------------------------------------------------------------');
    }
}
