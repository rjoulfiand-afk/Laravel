<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Kalau belum login, lempar balik ke halaman login
        if (!session('is_logged_in')) {
            return redirect('/');
        }
        
        // Kalau aman, silakan masuk
        return $next($request);
    }
}