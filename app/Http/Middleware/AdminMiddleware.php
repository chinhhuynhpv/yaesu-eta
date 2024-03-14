<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ( !Auth::guard('admin')->user()->is_admin ) {
            abort(403);
        }

        return $next($request);
    }
}
