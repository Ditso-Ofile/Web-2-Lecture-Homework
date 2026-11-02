<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * TASK 3 + TASK 9 - Only the admin (users.role = 1) may pass.
 * Registered in bootstrap/app.php as the 'admin' middleware alias.
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || $request->user()->role != 1) {
            return redirect()->route('home')->with('failed', 'Only the admin can use the CRUD menu.');
        }

        return $next($request);
    }
}
