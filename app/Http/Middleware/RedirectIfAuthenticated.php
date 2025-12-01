<?php

namespace App\Http\Middleware;

use App\Http\Middleware\Authenticate;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Jika user sudah login dan akses /login /register, arahkan ke dashboard role-nya.
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        if (Auth::check()) {
            $role = Auth::user()->role ?? 'user';
            $map  = [
                'admin'      => 'admin.dashboard',
                'technician' => 'technician.dashboard',
                'user'       => 'user.dashboard',
            ];

            $target = $map[$role] ?? 'user.dashboard';
            // Pakai route jika ada, fallback ke '/'
            return redirect()->route($target);
        }

        return $next($request);
    }
}
