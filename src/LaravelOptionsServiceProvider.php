<?php

namespace mradang\LaravelOptions;

use Illuminate\Support\ServiceProvider;

class LaravelOptionsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                \dirname(__DIR__) . '/config/options.php' => config_path('options.php'),
            ], 'config');

            $this->loadMigrationsFrom(\dirname(__DIR__) . '/migrations/');
        }

        $this->mergeConfigFrom(__DIR__ . '/../config/options.php', 'options');
    }

    public function register()
    {
        $this->app->singleton('laravel-options', function ($app) {
            return new OptionsManager($app);
        });
    }
}
