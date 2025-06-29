<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Keep Auth for potential future use
use Illuminate\Support\Facades\Log;
use App\Models\User; // Make sure User model is imported

class SharedLoginController extends Controller
{
    public function generateToken(Request $request)
    {
        // TEMPORARILY BYPASS AUTH FOR TESTING TOKEN GENERATION
        // Comment out or remove the original Auth::check() block
        /*
        if (!Auth::check()) {
            Log::warning('SharedLoginController: generateToken called by unauthenticated user.');
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        $user = Auth::user();
        */

        // FOR TESTING: Fetch a specific user or the first user.
        // Make sure you have at least one user in your ecommerce-app users table.
        $user = User::where('email', 'admin@gmail.com')->first();

        if (!$user) {
            Log::error('SharedLoginController: Test user not found for token generation bypass.');
            return response()->json(['error' => 'Test user not found for bypass. Please ensure a user exists.'], 404);
        }
        Log::info('SharedLoginController: generateToken (AUTH BYPASSED FOR TESTING) - Using user ID: ' . $user->id);

        $tokenName = 'shared-login-token-for-foodpanda';
        $token = $user->createToken($tokenName);
        $plainTextToken = $token->plainTextToken;

        $foodpandaAppUrl = rtrim(env('FOODPANDA_APP_URL', 'http://foodpanda.localhost'), '/');
        $autoLoginUrl = $foodpandaAppUrl . '/auto-login?token=' . $plainTextToken;
        
        Log::info('SharedLoginController: Token generated for user ' . $user->id . '. Auto-login URL: ' . $autoLoginUrl);

        return response()->json([
            'message' => 'Token generated successfully (AUTH BYPASSED FOR TESTING).',
            'access_token' => $plainTextToken,
            'token_type' => 'Bearer',
            'auto_login_url' => $autoLoginUrl,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }
}