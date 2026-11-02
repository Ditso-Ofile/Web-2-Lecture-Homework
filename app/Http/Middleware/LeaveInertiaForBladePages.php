<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

/**
 * The Login / Register pages come from the React (Inertia) starter kit, but our menu pages
 * are normal Blade pages. When the React login form redirects to a Blade page
 * (e.g. /dashboard -> /, or /messages after "please log in"), Inertia would receive plain HTML
 * and show an error popup. This middleware tells Inertia to do a normal full page load instead.
 */
class LeaveInertiaForBladePages
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $isInertiaRequest = $request->headers->has('X-Inertia');
        $isInertiaPage    = $response->headers->has('X-Inertia');

        if ($isInertiaRequest && $request->isMethod('GET') && ! $isInertiaPage && $response->getStatusCode() === 200) {
            // 409 + X-Inertia-Location header -> the browser loads the page normally
            return Inertia::location($request->fullUrl());
        }

        return $response;
    }
}
