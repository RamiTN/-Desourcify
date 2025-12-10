<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
{
    public function handle(Request $request, Closure $next)
    {
        // If no logged user OR not admin
        if (!$request->user() || $request->user()->is_admin !== true) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
