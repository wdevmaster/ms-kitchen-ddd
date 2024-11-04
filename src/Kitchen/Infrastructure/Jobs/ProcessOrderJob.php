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
        try {
            Log::debug('ProcessOrderJob', $this->request);
        } catch (\Exception $e) {
            $logChannel = Log::build([ 'driver' => 'single', 'path' => storage_path('logs/jobs.log')]);
            Log::stack([$logChannel])->error('ProcessOrderRequestJob => Error processing request: ' . $e->getMessage(), ['exception' => $e]);
        }
    }
}
