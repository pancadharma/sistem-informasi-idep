<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('change_language')) {
            session()->put('language', $request->input('change_language'));
            $language = $request->input('change_language');
        } elseif (session()->has('language')) {
            $language = session('language');
        } elseif (config('panel.primary_language')) {
            $language = config('panel.primary_language');
        } else {
            $language = $request->getPreferredLanguage(['id', 'en' ]); // Add more languages as needed
        }

        if (isset($language)) {
            app()->setLocale($language);
        }

        return $next($request);
    }
}
