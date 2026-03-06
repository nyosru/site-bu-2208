<?php

namespace App\Providers;

use App\Domain\Catalog\Repositories\CatalogReadRepositoryInterface;
use App\Domain\Advertisement\Repositories\AdvertisementRepositoryInterface;
use App\Infrastructure\Persistence\Repositories\Catalog\EloquentCatalogReadRepository;
use App\Infrastructure\Persistence\Repositories\EloquentAdvertisementRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AdvertisementRepositoryInterface::class, EloquentAdvertisementRepository::class);
        $this->app->bind(CatalogReadRepositoryInterface::class, EloquentCatalogReadRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
