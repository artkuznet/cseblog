<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Ramsey\Uuid\Uuid;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['validator']->extend('uuid', function ($attribute, $value, $parameters)
        {
            return Uuid::isValid($value);
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}