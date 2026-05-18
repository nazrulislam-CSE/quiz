<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBkToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $bktoken = $request->header('bktoken');
        
        // Get token from env file for better security
        $validToken = env('BK_TOKEN', 'k7m3qz2zmmp9oux4ghnz10g6l90r77po8v5br4svw6pf5j5qe9fvxr6d849amvsj');
        
        // Check if token exists and matches
        if (!$bktoken || $bktoken !== $validToken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or missing bktoken. You are not authorized to access this API.'
            ], 403);
        }

        return $next($request);
    }
}