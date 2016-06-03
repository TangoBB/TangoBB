<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Tango\Libraries\Bbcode as Bbcode;

class ForumServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton(Bbcode::class, function() {
            return new Bbcode();
        });
    }
}
