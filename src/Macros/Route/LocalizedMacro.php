<?php

namespace LaravelToolbox\LocalizedRoutes\Macros\Route;

use LaravelToolbox\LocalizedRoutes\LocalizedRoutesRegistrar;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

class LocalizedMacro
{
    /**
     * Register the macro.
     *
     * @return void
     */
    public static function register()
    {
        Route::macro('localized', function ($closure, $options = []) {
            App::make(LocalizedRoutesRegistrar::class)->register($closure, $options);
        });
    }
}
