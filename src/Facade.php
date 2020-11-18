<?php

namespace mradang\LaravelOptions;

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-options';
    }
}