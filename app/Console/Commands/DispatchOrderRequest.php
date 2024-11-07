<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Kitchen\Infrastructure\Jobs\GenerateOrderJob;

class DispatchOrderRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        GenerateOrderJob::dispatch([
            'number_dishes' => 1
        ]);
    }
}
