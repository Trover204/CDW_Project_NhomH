<?php

namespace App\Providers;

use App\Models\Payment;
use App\Scout\Engines\ElasticsearchEngine;
use App\Scout\Factories\OptimizedSearchParametersFactory;
use Elastic\ScoutDriver\Factories\SearchParametersFactoryInterface;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\Builder;
use Laravel\Scout\EngineManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            SearchParametersFactoryInterface::class,
            OptimizedSearchParametersFactory::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Relation::morphMap(Payment::SOURCES);

        Paginator::defaultView('vendor.pagination.admin');

        resolve(EngineManager::class)->extend('elasticsearch', function ($app) {
            return new ElasticsearchEngine(
                config('scout.elasticsearch', []),
                (bool) config('scout.soft_delete', false),
                $app->make(OptimizedSearchParametersFactory::class),
            );
        });

        Builder::macro('searchAfter', function (array $cursor): Builder {
            /** @var Builder $this */
            $this->options['search_after'] = array_values($cursor);

            return $this;
        });

        Builder::macro('onlySource', function (array|bool $fields): Builder {
            /** @var Builder $this */
            $this->options['_source'] = $fields;

            return $this;
        });
    }
}
