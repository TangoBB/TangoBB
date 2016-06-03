<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Validator;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Validator::extend('AuthCheck', 'App\Tango\Libraries\Validator@AuthCheck');
        Validator::extend('AuthPermission', 'App\Tango\Libraries\Validator@AuthPermission');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
