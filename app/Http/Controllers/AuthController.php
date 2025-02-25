<?php

namespace App\Http\Controllers;

use App\Models\UserToken;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // return $request;
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        // if (!$user || !Hash::check($request->password, $user->password)) {
        //     return response()->json(['message' => 'Invalid credentials'], 401);
        // }

        // Generate token
        // $token = $user->createToken('Personal Access Token')->plainTextToken;

        // $user = auth()->user();
        $user = Auth::user();
        // return response()->json( $user);

        // Generate a new token
        $token = hash('sha256', Str::random(60));
        // $token = $user->createToken('Personal Access Token')->plainTextToken;

        // Store token in database
        $userToken = UserToken::create([
            'userid' => $user->id,
            'token' => $token,
            'expires_at' => Carbon::now()->addHours(720), // Token expires in 2 hours
        ]);

        return response()->json([
            'userid' => $user->id,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => $userToken->expires_at,
        ]);
    }

    public function logout(Request $request)
    {
        // Retrieve the token from the Authorization header (Bearer token)
        $token = $request->bearerToken();
        // return response()->json($token);
    
        // If no token is provided, return an unauthorized response
        if (!$token) {
            return response()->json(['message' => 'No token provided. Unauthorized'], 401);
        }
    
        // Find and delete the token from the UserToken table
        $userToken = UserToken::where('token', $token)->first();
    
        // If the token doesn't exist in the database, return an error response
        if (!$userToken) {
            return response()->json(['message' => 'Token not found or already deleted'], 404);
        }
    
        // Delete the token from the database
        $userToken->delete();
    
        // Optionally: Log out the user using the same token and ensure the auth session is cleared
        Auth::logout();
    
        // Return a success response
        return response()->json(['message' => 'Logged out successfully']);
    }
    
}
