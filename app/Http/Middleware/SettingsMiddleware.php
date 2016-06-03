<?php

namespace App\Http\Middleware;

use Closure;
use Theme;

use App\Tango\Database\Settings as Settings;

class SettingsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //Sharing Settings with all views and setting default theme.
        $settings = Settings::first();
        Theme::set($settings['forum_theme']);
        view()->share('settings', $settings);
        return $next($request);
    }
}
