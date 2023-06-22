<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FindUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->route('id'); 
        $user = User::find($id); 

        if (!$user) { 
            return response()->json([
                'success' => false, 
                'message' => 'Not found User',
                'status_code' => 404,
            ], 
            404);
        }

        return $next($request); 
    }
}
