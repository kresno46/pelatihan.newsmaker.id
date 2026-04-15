<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        if (! $user) {
            abort(401); // Unauthorized, belum login
        }

        $allowedRoles = collect($roles)
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->values()
            ->all();

        if (in_array($user->role, $allowedRoles, true)) {
            return $next($request);
        }

        abort(403); // Forbidden, login tapi bukan Admin
    }
}
