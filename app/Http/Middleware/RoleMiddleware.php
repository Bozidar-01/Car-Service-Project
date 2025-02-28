<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to access this page.');
        }

        // Check if the authenticated user has the 'admin' role
        if (auth()->user()->role === 'admin') {
            return $next($request);
        }else{
            auth()->logout();

            return redirect()->route('login')
            ->with('error', 'You are not authorized to access this page.');
        }

    }
}
