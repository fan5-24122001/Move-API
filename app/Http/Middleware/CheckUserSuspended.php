<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserSuspended
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $suspend = User::where('email', $request->input('email'))->where('is_suspended', 1)->exists();
        if($suspend){
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Your account has been suspended. Pls contact to Admin!',
                    'status_code' => 403,
                ], 403
            );
        }

        return $next($request);
    }
}
