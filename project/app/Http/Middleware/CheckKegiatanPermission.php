<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckKegiatanPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        // Redirect to login if not logged in (redundant if you're using 'auth' middleware)
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Allow Super Admin (ID = 1)
        if ($user->id === 1) {
            return $next($request);
        }

        // Check permission using Spatie's can()
        if ($user->can($permission)) {
            return $next($request);
        }

        // If unauthorized, show 403 page
        abort(403, 'You do not have permission to access this page.');
    }
}
