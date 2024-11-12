<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AWS\Queues\Connector;

class EventQueueServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app['queue']->extend('event-queue', function () {
            return new Connector;
        });
    }
}
