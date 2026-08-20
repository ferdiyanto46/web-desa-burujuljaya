<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! method_exists($response, 'header')) {
            return $response;
        }

        $response->header('X-Content-Type-Options', 'nosniff');
        $response->header('X-Frame-Options', 'SAMEORIGIN');
        $response->header('X-XSS-Protection', '1; mode=block');
        if (app()->environment('production')) {
            $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }
        $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->header('Cross-Origin-Resource-Policy', 'cross-origin');
        $response->header('Permissions-Policy', 'geolocation=*, microphone=(), camera=()');

        // Content Security Policy
        // Allow Google Maps, OpenStreetMap, and fonts
        $csp = "default-src 'self'; ".
               "script-src 'self' 'unsafe-inline' 'unsafe-eval'; ".
               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; ".
               "font-src 'self' https://fonts.gstatic.com; ".
               "img-src 'self' data: https:; ".
               "object-src 'none'; ".
               "frame-src 'self' https://www.google.com https://maps.google.com https://*.google.com https://*.openstreetmap.org; ".
               "connect-src 'self' https:; ";

        if (app()->environment('production')) {
            $csp .= 'upgrade-insecure-requests;';
        }

        $response->header('Content-Security-Policy', $csp);

        return $response;
    }
}
