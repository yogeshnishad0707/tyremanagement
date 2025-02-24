<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserToken;

class TokenAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'No token provided'], 401);
        }

        // Retrieve token from database
        $userToken = UserToken::where('token', $token)->first();

        // Debugging - Check if token is found
        if (!$userToken) {
            return response()->json(['error' => 'Invalid Token'], 401);
        }

        // Debugging - Check if token is expired
        if (now()->greaterThan($userToken->expires_at)) {
            return response()->json(['error' => 'Token expired', 'expires_at' => $userToken->expires_at], 401);
        }

        return $next($request);
    }
}
