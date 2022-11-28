<?php

namespace mradang\LaravelOptions;

use Illuminate\Support\ServiceProvider;

class LaravelOptionsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(\dirname(__DIR__) . '/migrations/');
        }
    }

    public function register()
    {
        $this->app->singleton('laravel-options', function ($app) {
            return new OptionsManager($app);
        });
    }
}
