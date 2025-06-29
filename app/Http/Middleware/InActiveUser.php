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
        // if(auth()->check() && auth()->user()->active == 0){
        //     return $next($request);
        // }else{
        //     return redirect()->to('/home');
        // }

         // If not logged in, redirect to login
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    // If inactive, allow
    if (auth()->user()->active == 0) {
        return $next($request);
    }

    // If active, redirect away
    return redirect()->to('/home');
    }
}
