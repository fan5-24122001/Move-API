<?php

namespace App\Http\Middleware;

use App\Models\Report;
use App\Models\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkReportedThisUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $reported = Report::where('user_id', auth()->user()->id)->where('reported_user_id', $request->route('id'))->exists();
        
        if ($reported) {
            return new JsonResponse([
                'success' => false,
                'message' => 'You has been reported this user.',
                'status_code' => 403,
            ], 403);
        }
        
        return $next($request);
    }
}
