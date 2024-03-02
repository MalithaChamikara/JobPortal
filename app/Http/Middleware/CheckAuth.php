<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //auth()->check() chekcs if the current user is logged in or not
        //if the user is logged redirect to the home page
        if (!auth()->check()) {
            return $next($request);
        }
        return redirect()->to('/');
    }
}
