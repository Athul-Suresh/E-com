<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {


          // Check if the authenticated user is an admin
          if (auth()->guard('admin')->check()) {
            return $next($request);
        }

        // Redirect or return a response for unauthorized access
        return redirect()->route('admin.login')->with('error', 'Unauthorized access.');

    }
}
