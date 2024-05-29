<?php

if (!function_exists('theme_asset')) {
    function theme_asset($path = null): string
    {
        $theme_name = env('WEB_THEME') == null ? 'default' : env('WEB_THEME');
        return asset("resources/themes/$theme_name/public/$path");
    }
}

if (!function_exists('theme_root_path')) {
    function theme_root_path(): string
    {
        $theme_name = env('WEB_THEME') == null ? 'default' : env('WEB_THEME');
        return $theme_name;
    }
}


