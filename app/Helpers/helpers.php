<?php

if (!function_exists('locale_route')) {
    /**
     * Generate a route for the current locale
     */
    function locale_route(string $name, array $parameters = []): string
    {
        $locale = app()->getLocale();

        // If not English, prefix the route name with locale
        if ($locale !== 'en') {
            $name = $locale . '.' . $name;
        }

        return route($name, $parameters);
    }
}

if (!function_exists('current_route_name')) {
    /**
     * Get the current route name without locale prefix
     */
    function current_route_name(): ?string
    {
        $routeName = request()->route()->getName();

        if (!$routeName) {
            return null;
        }

        // Remove locale prefix if present
        return preg_replace('/^(nl|id)\./', '', $routeName);
    }
}

if (!function_exists('is_active_route')) {
    /**
     * Check if the given route name matches the current route
     */
    function is_active_route(string $name): bool
    {
        $currentName = current_route_name();
        return $currentName === $name;
    }
}
