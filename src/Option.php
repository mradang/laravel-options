<?php

namespace mradang\LaravelOptions;

class Option extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-options';
    }
}