<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

class ServiceApiEndpointsTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function authenticated_user_can_get_services_list()
    {
        Passport::actingAs($this->user);
        
        Service::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/v1/services');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'category',
                        'description',
                        'website_url',
                        'user_id',
                        'subscriptions_count',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }

    /** @test */
    public function authenticated_user_can_filter_services_by_name()
    {
        Passport::actingAs($this->user);
        
        Service::factory()->create(['user_id' => $this->user->id, 'name' => 'Netflix']);
        Service::factory()->create(['user_id' => $this->user->id, 'name' => 'Spotify']);

        $response = $this->getJson('/api/v1/services?name=Netflix');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function authenticated_user_can_filter_services_by_category()
    {
        Passport::actingAs($this->user);
        
        Service::factory()->create(['user_id' => $this->user->id, 'category' => 'Streaming']);
        Service::factory()->create(['user_id' => $this->user->id, 'category' => 'Music']);

        $response = $this->getJson('/api/v1/services?category=Streaming');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function authenticated_user_can_create_service()
    {
        Passport::actingAs($this->user);
        
        $serviceData = [
            'name' => 'Netflix',
            'category' => 'Streaming',
            'description' => 'Video streaming service',
            'website_url' => 'https://netflix.com'
        ];

        $response = $this->postJson('/api/v1/services', $serviceData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'name',
                    'category',
                    'description',
                    'website_url',
                    'user_id',
                    'subscriptions_count',
                    'created_at',
                    'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('services', [
            'name' => 'Netflix',
            'category' => 'Streaming',
            'user_id' => $this->user->id
        ]);
    }

    /** @test */
    public function service_creation_fails_with_invalid_data()
    {
        Passport::actingAs($this->user);
        
        $invalidData = [
            'name' => '',
            'category' => '',
            'website_url' => 'invalid-url'
        ];

        $response = $this->postJson('/api/v1/services', $invalidData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'category', 'website_url']);
    }

    /** @test */
    public function authenticated_user_can_view_single_service()
    {
        Passport::actingAs($this->user);
        
        $service = Service::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson("/api/v1/services/{$service->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'name',
                    'category',
                    'description',
                    'website_url',
                    'user_id',
                    'subscriptions_count',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    /** @test */
    public function user_cannot_view_other_users_service()
    {
        Passport::actingAs($this->user);
        
        $otherUser = User::factory()->create();
        $service = Service::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->getJson("/api/v1/services/{$service->id}");

        $response->assertStatus(404);
    }

    /** @test */
    public function authenticated_user_can_update_service()
    {
        Passport::actingAs($this->user);
        
        $service = Service::factory()->create(['user_id' => $this->user->id]);
        
        $updateData = [
            'name' => 'Updated Netflix',
            'category' => 'Entertainment',
            'description' => 'Updated description',
            'website_url' => 'https://updated-netflix.com'
        ];

        $response = $this->putJson("/api/v1/services/{$service->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data'
            ]);

        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'name' => 'Updated Netflix',
            'category' => 'Entertainment'
        ]);
    }

    /** @test */
    public function authenticated_user_can_partially_update_service()
    {
        Passport::actingAs($this->user);
        
        $service = Service::factory()->create(['user_id' => $this->user->id, 'name' => 'Original Name']);
        
        $updateData = [
            'name' => 'Partially Updated Name'
        ];

        $response = $this->patchJson("/api/v1/services/{$service->id}", $updateData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'name' => 'Partially Updated Name'
        ]);
    }

    /** @test */
    public function user_cannot_update_other_users_service()
    {
        Passport::actingAs($this->user);
        
        $otherUser = User::factory()->create();
        $service = Service::factory()->create(['user_id' => $otherUser->id]);
        
        $updateData = [
            'name' => 'Hacked Service'
        ];

        $response = $this->putJson("/api/v1/services/{$service->id}", $updateData);

        $response->assertStatus(404);
    }

    /** @test */
    public function authenticated_user_can_delete_service()
    {
        Passport::actingAs($this->user);
        
        $service = Service::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("/api/v1/services/{$service->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Service deleted successfully'
            ]);

        $this->assertDatabaseMissing('services', [
            'id' => $service->id
        ]);
    }

    /** @test */
    public function user_cannot_delete_other_users_service()
    {
        Passport::actingAs($this->user);
        
        $otherUser = User::factory()->create();
        $service = Service::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->deleteJson("/api/v1/services/{$service->id}");

        $response->assertStatus(404);
    }

    /** @test */
    public function authenticated_user_can_get_categories_list()
    {
        Passport::actingAs($this->user);
        
        Service::factory()->create(['user_id' => $this->user->id, 'category' => 'Streaming']);
        Service::factory()->create(['user_id' => $this->user->id, 'category' => 'Music']);
        Service::factory()->create(['user_id' => $this->user->id, 'category' => 'Streaming']);

        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data'
            ])
            ->assertJsonCount(2, 'data');
    }

    /** @test */
    public function unauthenticated_user_cannot_access_services_endpoints()
    {
        $response = $this->getJson('/api/v1/services');
        $response->assertStatus(401);

        $response = $this->postJson('/api/v1/services', []);
        $response->assertStatus(401);

        $response = $this->getJson('/api/v1/services/1');
        $response->assertStatus(401);

        $response = $this->putJson('/api/v1/services/1', []);
        $response->assertStatus(401);

        $response = $this->deleteJson('/api/v1/services/1');
        $response->assertStatus(401);

        $response = $this->getJson('/api/v1/categories');
        $response->assertStatus(401);
    }
}