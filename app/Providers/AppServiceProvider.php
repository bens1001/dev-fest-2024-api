<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DataProcessingService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DataProcessingService::class, function ($app) {
            return new DataProcessingService(
                storage_path('app/test_set_rec.csv'),
                env('FLASK_MODEL_URL')
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
