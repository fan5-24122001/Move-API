<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEmailExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $email = $request->input('email');
        if (! User::where('email', $email)->exists()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => "Your email doesn't exist, please type a correct email !!!",
                    'status_code' => 404,
                ], 404
        );
        }

        return $next($request);
    }
}
