<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserAkses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request):$next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if(auth()->user()->role == $role){
            return $next($request);
        }
        return redirect('admin');
    }
}
