<?php

if (!function_exists('localized_route')) {
    /**
     * Generate a localized URL for the given route.
     *
     * @param  string  $name
     * @param  mixed  $parameters
     * @param  bool  $absolute
     * @return string
     */
    function localized_route($name, $parameters = [], $absolute = true)
    {
        $locale = app()->getLocale();
        
        // Prepend locale to parameters
        if (is_array($parameters)) {
            $parameters = array_merge(['locale' => $locale], $parameters);
        } else {
            $parameters = ['locale' => $locale, $parameters];
        }
        
        return route($name, $parameters, $absolute);
    }
}

if (!function_exists('localized_url')) {
    /**
     * Generate a localized URL for the given path.
     *
     * @param  string  $path
     * @return string
     */
    function localized_url($path = '')
    {
        $locale = app()->getLocale();
        $path = ltrim($path, '/');
        
        return url('/' . $locale . ($path ? '/' . $path : ''));
    }
}
