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
        Log::stack([$logChannel])->debug('ProcessOrderJob::request', $this->request);
        try {
            Log::stack([$logChannel])->debug('ProcessOrderJob::Success -------------------------------------------------------------------------');
            $processOrderService->__invoke($this->request);
        } catch (\Throwable $th) {
            Log::stack([$logChannel])->error($th->getMessage(), ['exception' => $th]);
        }
        Log::stack([$logChannel])->debug('ProcessOrderJob::end --------------------------------------------------------------------------');
    }
}
