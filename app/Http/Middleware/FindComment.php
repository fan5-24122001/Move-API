<?php

namespace App\Http\Middleware;

use App\Models\Comment;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FindComment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $idComment = $request->route('idComment'); 
        $comment = Comment::find($idComment); 

        if (!$comment) { 
            return response()->json([
                'success' => false, 
                'message' => 'Not found Comment',
                'status_code' => 404,
            ], 
            404);
        }

        return $next($request); 
    }
}
