<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Rules\StrongPassword;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private function json($success, $message, $data = null, $status = 200)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    private function ensurePersonalAccessClient(): void
    {
        // Simply check if personal access client exists, don't try to create
        // This should be handled by `php artisan passport:install` during deployment
        if (!Schema::hasTable('oauth_personal_access_clients') || !Schema::hasTable('oauth_clients')) {
            return;
        }
        
        // If no personal access client exists, this is a deployment issue
        // The application should fail gracefully or use existing clients
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', new StrongPassword()]
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        $this->ensurePersonalAccessClient();
        $token = $user->createToken('api')->accessToken;

        return $this->json(true, 'Registered', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ],
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return $this->json(false, 'Invalid credentials', null, 401);
        }

        $this->ensurePersonalAccessClient();
        $token = $user->createToken('api')->accessToken;

        return $this->json(true, 'Login successful', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ],
            'token' => $token
        ], 200);
    }

    public function logout(Request $request)
    {
        
        // $request->user()->token()->revoke();
        
        $request->user()->tokens()->delete();
        
        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        
        return $this->json(true, 'Profile retrieved successfully', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ?? 'user'
            ]
        ]);
    }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', new StrongPassword()],
        ]);

        $user = $request->user();
        
        if (!Hash::check($data['current_password'], $user->password)) {
            return $this->json(false, 'Current password is incorrect', null, 400);
        }

        $user->update([
            'password' => Hash::make($data['new_password'])
        ]);

        return $this->json(true, 'Password changed successfully');
    }
}
