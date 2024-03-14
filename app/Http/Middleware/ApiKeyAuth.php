<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKeyAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $apiKeyApp = config('api.api_key');
        $apiKeyAuth = $request->header('x-api-key');
        if ($apiKeyApp != $apiKeyAuth) {
            return response()->json([
                'errors' => "Bad request",
                'status_code' => 412
            ], 412);
        }

        return $next($request);
    }
}
