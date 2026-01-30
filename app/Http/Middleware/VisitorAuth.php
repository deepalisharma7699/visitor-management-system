<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitorAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('visitor')->check()) {
            return redirect()->route('visitor.login')
                ->with('error', 'Please login to access this page.');
        }

        return $next($request);
    }
}
