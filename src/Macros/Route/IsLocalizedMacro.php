<?php

namespace LaravelToolbox\LocalizedRoutes\Macros\Route;

use LaravelToolbox\LocalizedRoutes\RouteHelper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

class IsLocalizedMacro
{
    /**
     * Register the macro.
     *
     * @return void
     */
    public static function register()
    {
        Route::macro('isLocalized', function ($patterns = null, $locales = '*') {
            return App::make(RouteHelper::class)->isLocalized($patterns, $locales);
        });
    }
}
