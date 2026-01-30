<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            // Check which guard is being used based on the route
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }
            
            if ($request->is('visitor') || $request->is('visitor/*')) {
                return route('visitor.login');
            }
            
            // Default to admin login for any other protected routes
            return route('admin.login');
        }

        return null;
    }
}
