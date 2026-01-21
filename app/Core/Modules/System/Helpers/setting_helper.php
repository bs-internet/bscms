<?php

if (!function_exists('get_setting')) {
    function get_setting(string $key, $default = null)
    {
        $template = service('template');
        return $template->getSetting($key, $default);
    }
}

if (!function_exists('the_setting')) {
    function the_setting(string $key, $default = null): void
    {
        echo get_setting($key, $default);
    }
}