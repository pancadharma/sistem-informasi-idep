<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Set Cache-Control for specific file types (customize as needed)
        if (strpos($request->getRequestUri(), '.css') !== false) {
            $response->headers->set('Cache-Control', 'max-age=1209600, public');
        } elseif (strpos($request->getRequestUri(), '.js') !== false) {
            $response->headers->set('Cache-Control', 'max-age=1209600, public');
        } elseif (strpos($request->getRequestUri(), '.jpg') !== false || strpos($request->getRequestUri(), '.jpeg') !== false || strpos($request->getRequestUri(), '.png') !== false || strpos($request->getRequestUri(), '.gif') !== false || strpos($request->getRequestUri(), '.svg') !== false) {
            $response->headers->set('Cache-Control', 'max-age=2592000, public'); // One month for images
        }

        return $response;
    }
}