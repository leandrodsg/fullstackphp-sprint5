<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceCreationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_create_service_with_valid_data()
    {
        $user = User::factory()->create();
        
        $validServiceData = [
            'name' => 'Netflix',
            'category' => 'Streaming',
            'description' => 'Video streaming service'
        ];

        $response = $this->actingAs($user)
            ->post('/services', $validServiceData);

        $response->assertRedirect('/services')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('services', [
            'name' => 'Netflix',
            'category' => 'Streaming', 
            'description' => 'Video streaming service',
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function service_creation_fails_with_missing_required_fields()
    {
        $user = User::factory()->create();
        
        $invalidData = [
            'description' => 'Missing name and category'
        ];

        $response = $this->actingAs($user)
            ->post('/services', $invalidData);

        $response->assertSessionHasErrors(['name', 'category']);
        $this->assertDatabaseCount('services', 0);
    }

    /** @test */
    public function service_creation_fails_with_invalid_category()
    {
        $user = User::factory()->create();
        
        $invalidCategoryData = [
            'name' => 'Test Service',
            'category' => 'InvalidCategory',
            'description' => 'Test description'
        ];

        $response = $this->actingAs($user)
            ->post('/services', $invalidCategoryData);

        $response->assertSessionHasErrors(['category']);
        $this->assertDatabaseCount('services', 0);
    }

    /** @test */
    public function service_name_must_be_unique_per_user()
    {
        $user = User::factory()->create();
        
        Service::factory()->create([
            'name' => 'Netflix',
            'user_id' => $user->id
        ]);

        $duplicateNameData = [
            'name' => 'Netflix',
            'category' => 'Streaming',
            'description' => 'Another Netflix service'
        ];

        $response = $this->actingAs($user)
            ->post('/services', $duplicateNameData);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseCount('services', 1);
    }

    /** @test */
    public function different_users_can_create_services_with_same_name()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $serviceData = [
            'name' => 'Netflix',
            'category' => 'Streaming',
            'description' => 'Video streaming service'
        ];

        $this->actingAs($user1)
            ->post('/services', $serviceData)
            ->assertRedirect('/services');

        $this->actingAs($user2)
            ->post('/services', $serviceData)
            ->assertRedirect('/services');

        $this->assertDatabaseCount('services', 2);
        
        $this->assertDatabaseHas('services', [
            'name' => 'Netflix',
            'user_id' => $user1->id
        ]);
        
        $this->assertDatabaseHas('services', [
            'name' => 'Netflix', 
            'user_id' => $user2->id
        ]);
    }

    /** @test */
    public function unauthenticated_user_cannot_create_service()
    {
        $serviceData = [
            'name' => 'Netflix',
            'category' => 'Streaming',
            'description' => 'Video streaming service'
        ];

        $response = $this->post('/services', $serviceData);

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('services', 0);
    }
}