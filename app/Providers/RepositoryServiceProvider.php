<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Kitchen\Domain\Repositories AS DomainKitchen;
use Kitchen\Infrastructure\Persistence\Repositories AS InfrastructureKitchen;

class RepositoryServiceProvider extends ServiceProvider
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

    }
}
