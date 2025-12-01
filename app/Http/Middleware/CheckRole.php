<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Pakai: ->middleware('role:admin') atau ->middleware('role:admin,technician')
     * atau | sebagai pemisah: role:admin|technician
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Gabungkan semua argumen, dukung comma/pipe
        $normalized = [];
        foreach ($roles as $r) {
            foreach (preg_split('/[,\|]/', (string)$r) as $part) {
                $part = strtolower(trim($part));
                if ($part !== '') $normalized[] = $part;
            }
        }

        $userRole = strtolower(Auth::user()->role ?? '');

        if (empty($normalized) || !in_array($userRole, $normalized, true)) {
            // Untuk request API/AJAX balas JSON, lainnya 403 page
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
            abort(403, 'Unauthorized action. Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
