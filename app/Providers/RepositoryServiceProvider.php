<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Core\Kitchen\Domain\Repositories AS Domain;
use Core\Kitchen\Infrastructure\Persistence\Repositories AS Infrastructure;

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
