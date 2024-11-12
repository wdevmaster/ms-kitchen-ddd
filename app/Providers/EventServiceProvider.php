<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\AWS\Events\EventPublisherInterface;
use App\Services\AWS\Events\EventPublisher;

use Aws\Sns\SnsClient;

class EventServiceProvider extends ServiceProvider
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
        $this->registerEventPublisher();
    }

    private function registerEventPublisher()
    {
        $this->app->bind(SnsClient::class, function () {
            return new SnsClient([
                'region' => config("services.sns.region"),
                'version' => config('services.sns.version'),
                'credentials' => config('services.sns.credentials'),
            ]);
        });
        $this->app->bind(EventPublisherInterface::class, EventPublisher::class);

    }
}
