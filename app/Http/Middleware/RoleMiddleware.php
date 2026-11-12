<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan pengguna telah login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Cek apakah role pengguna saat ini terdaftar di parameter $roles
        if (!in_array($request->user()->role, $roles)) {
            abort(403, 'Anda tidak memiliki wewenang untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
