<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function unauthenticated($request, array $guards)
    {
        if (!$request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        };
    }
}

// namespace App\Http\Middleware;

// use Illuminate\Auth\Middleware\Authenticate as Middleware;
// use Illuminate\Http\Request;
// use Illuminate\Http\Response;

// class Authenticate extends Middleware
// {
//     /**
//      * Handle an unauthenticated user.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  array  $guards
//      * @return void
//      *
//      * @throws \Illuminate\Http\Exceptions\HttpResponseException
//      */
//     protected function unauthenticated($request, array $guards)
//     {
//         // if (!$request->expectsJson()) {
//             return response()->json(['message' => 'Unauthenticated.'], 401);
//         // }
//     }
// }
