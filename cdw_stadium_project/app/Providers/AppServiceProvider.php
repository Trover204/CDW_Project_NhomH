<?php

namespace App\Providers;

use App\Models\Payment;
use App\Scout\Engines\ElasticsearchEngine;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\EngineManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Relation::morphMap(Payment::SOURCES);

        Paginator::defaultView('vendor.pagination.admin');

        resolve(EngineManager::class)->extend('elasticsearch', function () {
            return new ElasticsearchEngine(
                config('scout.elasticsearch', []),
                (bool) config('scout.soft_delete', false),
            );
        });
    }
}
