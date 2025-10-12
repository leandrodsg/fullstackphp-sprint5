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
        if (!Schema::hasTable('oauth_personal_access_clients') || !Schema::hasTable('oauth_clients')) {
            return;
        }
        if (DB::table('oauth_personal_access_clients')->count() > 0) {
            return;
        }
        
        // Insert client directly with proper boolean casting for PostgreSQL
        $clientId = Str::uuid()->toString();
        DB::statement("
            INSERT INTO oauth_clients (id, name, secret, provider, redirect_uris, grant_types, scopes, personal_access_client, password_client, revoked, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?::boolean, ?::boolean, ?::boolean, ?, ?)
        ", [
            $clientId,
            'Personal Access Client',
            Str::random(40),
            'users',
            json_encode([]),
            json_encode(['personal_access', 'refresh_token']),
            json_encode([]),
            'true',
            'false',
            'false',
            now(),
            now()
        ]);
        
        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $clientId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
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
