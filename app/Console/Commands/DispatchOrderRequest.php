<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\IngredientsRequest;

class DispatchOrderRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dispatch-order';

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
        IngredientsRequest::dispatch([
            'ms-kitchen' => 1
        ]);
    }
}
