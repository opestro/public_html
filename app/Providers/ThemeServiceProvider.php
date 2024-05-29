<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $theme = env('WEB_THEME') == null ? 'default' : env('WEB_THEME');
        $path = base_path('resources/themes/' . $theme);
        if (!defined('VIEW_FILE_NAMES')) {
            define("VIEW_FILE_NAMES", include($path . '/file_names.php'));
        }
        view()->addLocation($path);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
