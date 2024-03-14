<?php

namespace App\Http\Middleware;

use App\Models\MUserRequest;
use Closure;
use Illuminate\Http\Request;

class UserRequestPrevention
{
    public function handle(Request $request, Closure $next)
    {
        if (($id = $request->id) || ($id = $request->request_id)) {
            $userRequest = MUserRequest::find($id);

            if ($userRequest && $userRequest->getRawValue('status') == 2) {
                abort(403);
            }
        }

        return $next($request);
    }
}
