<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUsername
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $username = $request->input('username');

        if ($username !== auth()->user()->username) {

            $usernameExist = User::where('username', $username)->first();

            if($usernameExist){
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Username already exists, Please enter another username!',
                        'status_code'=> 400,
                    ], 400
                );
            }
        }

        return $next($request);
    }
}
