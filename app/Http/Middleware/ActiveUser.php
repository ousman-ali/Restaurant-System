<?php

namespace App\Http\Middleware;

use Closure;

class ActiveUser
{
    /**
     * Handle an incoming request.
     * Redirect to account disable page if not active
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->check() && auth()->user()->active == 1){
            return $next($request);
        }else{
            return redirect()->to('/account-disable');
        }
    }
}
