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
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    public String $subject;
    public Array $payload;
    /**
     * Create a new job instance.
     */
    public function __construct(String $subject, Array $payload){

        $this->subject = $subject;
        $this->payload = $payload;

        Log::info('ProcessOrderJob::__construct', [
            'subject' => $this->subject,
            'payload' => $this->payload,
        ]);
    }

    /**
     * Execute the job.
     */
    public function handle(ProcessOrderService $processOrderService): void
    {
        $processOrderService->__invoke($this->payload);
    }
}
