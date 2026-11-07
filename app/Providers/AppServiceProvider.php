<?php

namespace App\Providers;

use App\Contracts\PaymentProcessor;
use App\Services\Payments\PaymentGatewayAdapter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            PaymentProcessor::class,
            PaymentGatewayAdapter::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
