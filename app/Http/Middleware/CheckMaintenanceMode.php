<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! setting('maintenance_mode', false)) {
            return $next($request);
        }

        // Never block anything for an authenticated admin — this covers
        // the Filament panel pages themselves AND their underlying
        // Livewire AJAX requests (e.g. /livewire/update), which do not
        // share the /secure-panel URL prefix and would otherwise get
        // caught by a path-based check.
        if (Auth::guard('admin')->check()) {
            return $next($request);
        }

        // Also let unauthenticated visitors reach the admin login page
        // itself, so maintenance mode can never lock an admin out of
        // signing in to turn it back off.
        $adminPath = config('app.admin_path', 'secure-panel');

        if ($request->is($adminPath) || $request->is("{$adminPath}/*")) {
            return $next($request);
        }

        // Livewire's shared update endpoint is used by both the admin
        // panel and customer-facing app. We can't distinguish which
        // panel a given /livewire/update call belongs to purely from
        // the URL, so if the admin guard check above didn't already
        // pass, and this isn't the admin path, block it as normal —
        // customer Livewire components should still be gated during
        // maintenance.
        return response()->view('maintenance', [], 503);
    }
}