<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, \Closure $next)
    {
        // Get first segment of URL
        $segment = $request->segment(1);

        // Check if first segment is a locale prefix (nl or id)
        if (in_array($segment, ['nl', 'id'])) {
            app()->setLocale($segment);
            session(['locale' => $segment]);
        } else {
            // Default to English (no prefix)
            app()->setLocale('en');
            session(['locale' => 'en']);
        }

        return $next($request);
    }
}
