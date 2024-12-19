<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Attempt to parse the token
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['message' => 'User not found or token is invalid.'], 401);
            }
        } catch (JWTException $e) {
            // Catch JWT exception if the token is invalid or expired
            return response()->json(['message' => 'Token is invalid or expired.'], 401);
        }

        return $next($request);
    }
}
