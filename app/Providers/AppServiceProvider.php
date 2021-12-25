<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Money\MoneyFormatter;
use Money\MoneyParser;
use MoneyConfiguration;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MoneyFormatter::class, function() {
            return MoneyConfiguration::defaultFormatter();
        });
        $this->app->singleton(MoneyParser::class, function() {
            return MoneyConfiguration::defaultParser();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
