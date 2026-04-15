<?php

namespace App\Http\Middleware;

use App\Models\ApupptFeatureSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApupptFeatureEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if ($user && in_array($user->role, ['Admin', 'Admin APUPPT'], true)) {
            return $next($request);
        }

        if (ApupptFeatureSetting::isEnabled()) {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error', 'Fitur APUPPT sedang dinonaktifkan oleh admin.');
    }
}
