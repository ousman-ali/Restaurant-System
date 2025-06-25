<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BarmanRoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if(auth()->check() && auth()->user()->role == 5 || auth()->user()->role == 2 || auth()->user()->role == 1){
            return $next($request);
        }else{
            return redirect()->back();
        }
    }
}
