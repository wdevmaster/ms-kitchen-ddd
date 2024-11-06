<?php

namespace Kitchen\Infrastructure\Jobs;

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
    public function handle(): void
    {
        $logChannel = Log::build([ 'driver' => 'single', 'path' => storage_path('logs/jobs.log')]);

        Log::stack([$logChannel])->debug('ProcessOrderJob::dispatch -------------------------------------------------------------------------');
        Log::stack([$logChannel])->debug('ProcessOrderService::request', $this->request);
        Log::stack([$logChannel])->debug('ProcessOrderService::end --------------------------------------------------------------------------');

        // try {
        //     Log::stack([$logChannel])->debug('ProcessOrderJob', $this->request);
        // } catch (\Exception $e) {
        //     $logChannel = Log::build([ 'driver' => 'single', 'path' => storage_path('logs/services.log')]);
        //     Log::stack([$logChannel])->error('ProcessOrderJob => Error processing order: ' . $e->getMessage(), ['exception' => $e]);
        // }
    }
}
