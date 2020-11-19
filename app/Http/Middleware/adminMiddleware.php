<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use App\User ;
use Response;

use Closure;

class adminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(is_null(Auth::User()->user_role))
        {
            return response()->json([
                "status" => "failed",
                "message" => "access rectricted only to admins",
            ]);

        }else{
            return $next($request);        
        }
    }
}
