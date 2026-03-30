<?php

namespace App\Providers;

use App\Domain\Advertisement\Repositories\AdvertisementRepositoryInterface;
use App\Domain\Catalog\Repositories\CatalogReadRepositoryInterface;
use App\Infrastructure\Persistence\Repositories\Catalog\EloquentCatalogReadRepository;
use App\Infrastructure\Persistence\Repositories\EloquentAdvertisementRepository;
use App\Support\AdvertisementEventPublisherInterface;
use App\Support\AdvertisementTaskProducerInterface;
use App\Support\KafkaAdvertisementEventPublisher;
use App\Support\KafkaAdvertisementTaskProducer;
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
        $this->app->bind(AdvertisementEventPublisherInterface::class, KafkaAdvertisementEventPublisher::class);
        $this->app->bind(AdvertisementTaskProducerInterface::class, KafkaAdvertisementTaskProducer::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
