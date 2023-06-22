<?php

namespace App\Http\Middleware;

use App\Models\Category;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FindCategory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->route('id'); 
        $category = Category::find($id); 

        if (!$category) { 
            return response()->json([
                'success' => false, 
                'message' => 'Not found Category!',
                'status_code' => 404,
            ], 
            404);
        }

        return $next($request); 
    }
}
