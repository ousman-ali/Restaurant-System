<?php

namespace App\Http\Middleware;

use Closure;

class InActiveUser
{
    /**
     * Handle an incoming request.
     * Only go next if active user
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->check() && auth()->user()->active == 0){
            return $next($request);
        }else{
            return redirect()->to('/home');
        }
    }
}
