<?php

namespace App\Helpers;

class LocaleHelper
{
    /**
     * Get the current locale
     */
    public static function current(): string
    {
        return app()->getLocale();
    }

    /**
     * Get all available locales
     */
    public static function available(): array
    {
        return config('app.available_locales', ['en']);
    }

    /**
     * Get locale names
     */
    public static function names(): array
    {
        return config('app.locale_names', ['en' => 'English']);
    }

    /**
     * Generate URL with locale prefix
     */
    public static function url(string $path = '', ?string $locale = null): string
    {
        $locale = $locale ?? self::current();
        $path = ltrim($path, '/');

        return url("/{$locale}" . ($path ? "/{$path}" : ''));
    }

    /**
     * Generate route with locale prefix
     */
    public static function route(string $name, array $parameters = [], ?string $locale = null): string
    {
        $locale = $locale ?? self::current();

        return route($name, array_merge(['locale' => $locale], $parameters));
    }
}
