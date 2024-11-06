<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Warehouse\Domain\Repositories AS DomainWarehouse;
use Warehouse\Infrastructure\Persistence\Repositories AS InfrastructureRepositories;

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
        #Kitchen
        $this->app->bind(DomainKitchen\DishRepository::class, InfrastructureKitchen\EloquentDishRepository::class);
        $this->app->bind(DomainKitchen\OrderRepository::class, InfrastructureKitchen\EloquentOrderRepository::class);

    }
}
