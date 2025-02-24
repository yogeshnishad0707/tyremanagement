<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    // User Login API
    public function login(Request $request)
    {
        // return $request;
        // Validate user credentials
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt authentication
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }

        // Retrieve authenticated user using the email instead of Auth::user()
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Generate a unique token
        $token = hash('sha256', Str::random(60));

        // Delete any existing token for the user
        UserToken::where('userid', $user->id)->delete();

        // Store new token in the database
        $userToken = UserToken::create([
            'userid' => $user->id,
            'token' => $token,
            'expires_at' => now()->addDays(1),
        ]);

        if (!$userToken) {
            return response()->json(['message' => 'Failed to generate token'], 500);
        }

        return response()->json([
            'userid' => $user->id,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => $userToken->expires_at,
        ]);
    }

    // User Logout API
    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'No token provided'], 401);
        }

        $userToken = UserToken::where('token', $token)->first();

        if (!$userToken) {
            return response()->json(['message' => 'Token not found or already expired'], 404);
        }

        // Delete the token from the database
        $userToken->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
