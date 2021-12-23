<?php

namespace App\Providers;

use App\Utils\MoneyConfiguration;
use Illuminate\Support\ServiceProvider;

class MoneyConfigurationProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('money', function() {
            return new MoneyConfiguration();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
