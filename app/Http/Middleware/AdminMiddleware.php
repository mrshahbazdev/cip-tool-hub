<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Only allow users with the 'admin' role.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        // Redirect non-admins to the dashboard or home
        return redirect()->route('dashboard')->with('error', 'Unauthorized access. Admin privileges required.');
    }
}