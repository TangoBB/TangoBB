<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Tango\Database\Settings as Settings;

use Auth;
use Theme;
use DB;

class ViewsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //Sharing Auth::user() with all views.
        view()->share('auth', Auth::user());

        //Sharing global Javascript files that enables TangoBB to work.
        view()->share('jquery', '<script src="' . asset('assets/js/jquery-1.12.4.min.js') . '"></script>');
        view()->share('tangobb_js', '<script src="' . route('Core::Js::RenderJavascript') . '"></script>');

        //Sharing global CSS files that enables TangoBB to work.
        view()->share('tangobb_css', '<link href="' . asset('assets/css/global.css') . '" rel="stylesheet" />');
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
