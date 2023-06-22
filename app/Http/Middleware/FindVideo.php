<?php

namespace App\Http\Middleware;

use App\Models\Video;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FindVideo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->route('id'); 
        $video = Video::find($id); 

        if (!$video) { 
            return response()->json([
                'success' => false, 
                'message' => 'Not found Video',
                'status_code' => 404,
            ], 
            404);
        }

        return $next($request); 
    }
}
