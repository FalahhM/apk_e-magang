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
     * @param  \Closure(\Illuminate\Http\Request): $next
     * @param  string  $role
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Memeriksa apakah pengguna sudah login dan memiliki role yang sesuai
        if (auth()->check() && auth()->user()->role == $role) {
            return $next($request); // Lanjutkan permintaan
        }

        // Redirect jika tidak sesuai dengan role
        return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}
