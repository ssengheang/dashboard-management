<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the request has a token
        $token = $request->bearerToken();

        if (is_null($token)) {
            // If no token is found, return an unauthorized response
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // If a token exists, proceed with the request
        return $next($request);
    }
}

