<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthApiEndpointsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function register_creates_user_and_returns_token()
    {
        $payload = [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'StrongPassword123!',
            'password_confirmation' => 'StrongPassword123!'
        ];

        $response = $this->postJson('/api/v1/register', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id','name','email'],
                    'token'
                ]
            ])
            ->assertJson([
                'success' => true,
            ]);
    }

    /** @test */
    public function register_fails_with_duplicate_email()
    {
        $first = [
            'name' => 'User One',
            'email' => 'dup@example.com',
            'password' => 'StrongPassword123!',
            'password_confirmation' => 'StrongPassword123!'
        ];
        $this->postJson('/api/v1/register', $first)->assertStatus(201);

        $second = [
            'name' => 'User Two',
            'email' => 'dup@example.com',
            'password' => 'StrongPassword123!',
            'password_confirmation' => 'StrongPassword123!'
        ];
        $this->postJson('/api/v1/register', $second)->assertStatus(422);
    }

    /** @test */
    public function login_returns_token_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('StrongPassword123!')
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'StrongPassword123!'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id','name','email'],
                    'token'
                ]
            ])
            ->assertJson([
                'success' => true,
            ]);
    }

    /** @test */
    public function login_fails_with_invalid_credentials()
    {
        $this->postJson('/api/v1/register', [
            'name' => 'Thiago',
            'email' => 'wronglogin@example.com',
            'password' => 'StrongPassword123!',
            'password_confirmation' => 'StrongPassword123!'
        ])->assertStatus(201);

        $this->postJson('/api/v1/login', [
            'email' => 'wronglogin@example.com',
            'password' => 'WrongPassword123!'
        ])->assertStatus(401);
    }

    /** @test */
    public function profile_returns_authenticated_user()
    {
        $register = $this->postJson('/api/v1/register', [
            'name' => 'Vini',
            'email' => 'vini@example.com',
            'password' => 'StrongPassword123!',
            'password_confirmation' => 'StrongPassword123!'
        ])->assertStatus(201);

        $token = $register->json('data.token');

        $response = $this->getJson('/api/v1/profile', [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [ 'user' => ['id','name','email'] ]
            ])
            ->assertJson([
                'success' => true
            ]);
    }

    /** @test */
    public function profile_requires_authentication()
    {
        $this->getJson('/api/v1/profile')->assertStatus(401);
    }

    /** @test */
    public function change_password_updates_password_and_allows_new_login()
    {
        $register = $this->postJson('/api/v1/register', [
            'name' => 'Carlos',
            'email' => 'carlos@example.com',
            'password' => 'StrongPassword123!',
            'password_confirmation' => 'StrongPassword123!'
        ])->assertStatus(201);

        $token = $register->json('data.token');

        $this->putJson('/api/v1/change-password', [
            'current_password' => 'StrongPassword123!',
            'new_password' => 'NewStrongPassword123!',
            'new_password_confirmation' => 'NewStrongPassword123!'
        ], [ 'Authorization' => 'Bearer ' . $token ])
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->postJson('/api/v1/login', [
            'email' => 'carlos@example.com',
            'password' => 'StrongPassword123!'
        ])->assertStatus(401);

        $this->postJson('/api/v1/login', [
            'email' => 'carlos@example.com',
            'password' => 'NewStrongPassword123!'
        ])->assertStatus(200);
    }

    /** @test */
    public function change_password_fails_with_wrong_current_password()
    {
        $register = $this->postJson('/api/v1/register', [
            'name' => 'Nina',
            'email' => 'nina@example.com',
            'password' => 'StrongPassword123!',
            'password_confirmation' => 'StrongPassword123!'
        ])->assertStatus(201);
        $token = $register->json('data.token');

        $this->putJson('/api/v1/change-password', [
            'current_password' => 'WRONG',
            'new_password' => 'NewStrongPassword123!',
            'new_password_confirmation' => 'NewStrongPassword123!'
        ], [ 'Authorization' => 'Bearer ' . $token ])
            ->assertStatus(400);
    }
}
