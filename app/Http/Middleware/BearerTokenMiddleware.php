<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BearerTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil token dari header Authorization
        $token = $request->bearerToken();

        // Cek apakah token sesuai
        if ($token !== 'nm23a9f4b2c7d') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Bearer token invalid'
            ], 401);
        }

        return $next($request);
    }
}
