<?php

namespace App\Http\Middleware;

use Closure;

class KitchenRoleCheck
{
    /**
     * Handle an incoming request.
     * Only go next if user role is kitchen
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->check() && auth()->user()->role == 3 || auth()->user()->role == 2 || auth()->user()->role == 1){
            return $next($request);
        }else{
            return redirect()->back();
        }
    }
}
