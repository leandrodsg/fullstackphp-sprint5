<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Passport\Passport;

class ApiBaseStructureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function api_base_controller_should_return_json_success_response()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->getJson('/api/v1/test-base-response');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data'
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Base response working'
            ]);
    }

    /** @test */
    public function api_should_return_standardized_error_response()
    {
        $response = $this->getJson('/api/v1/test-error-response');

        $response->assertStatus(400)
            ->assertJsonStructure([
                'success',
                'message',
                'data'
            ])
            ->assertJson([
                'success' => false,
                'message' => 'Test error message'
            ]);
    }

    /** @test */
    public function protected_routes_should_return_401_without_authentication()
    {
        $response = $this->getJson('/api/v1/services');

        $response->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }
}