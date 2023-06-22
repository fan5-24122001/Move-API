<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEmailVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $verified = User::where('email', $request->input('email'))->where('email_verified_at', NULL)->exists();
        if ($verified == false) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Email has been verified!',
                    'status_code' => 400,
                ], 400
            );
        }

        return $next($request);
    }
}
