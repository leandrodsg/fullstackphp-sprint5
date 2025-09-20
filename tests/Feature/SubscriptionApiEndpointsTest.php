<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

class SubscriptionApiEndpointsTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->service = Service::factory()->create(['user_id' => $this->user->id]);
    }

    /** @test */
    public function authenticated_user_can_get_subscriptions_list()
    {
        Passport::actingAs($this->user);
        
        Subscription::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'service_id' => $this->service->id
        ]);

        $response = $this->getJson('/api/v1/subscriptions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'plan',
                        'price',
                        'currency',
                        'status',
                        'next_billing_date',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }

    /** @test */
    public function unauthenticated_user_cannot_get_subscriptions_list()
    {
        $response = $this->getJson('/api/v1/subscriptions');
        $response->assertStatus(401);
    }

    /** @test */
    public function authenticated_user_can_create_subscription()
    {
        Passport::actingAs($this->user);

        $subscriptionData = [
            'service_id' => $this->service->id,
            'plan' => 'Premium',
            'price' => 29.99,
            'currency' => 'USD',
            'next_billing_date' => '2024-12-01',
            'status' => 'active'
        ];

        $response = $this->postJson('/api/v1/subscriptions', $subscriptionData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'plan',
                    'price',
                    'currency',
                    'status',
                    'user_id',
                    'service_id'
                ]
            ]);

        $this->assertDatabaseHas('subscriptions', [
            'plan' => 'Premium',
            'price' => 29.99,
            'user_id' => $this->user->id
        ]);
    }

    /** @test */
    public function authenticated_user_can_view_specific_subscription()
    {
        Passport::actingAs($this->user);
        
        $subscription = Subscription::factory()->create([
            'user_id' => $this->user->id,
            'service_id' => $this->service->id
        ]);

        $response = $this->getJson("/api/v1/subscriptions/{$subscription->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'plan',
                    'price',
                    'currency',
                    'status'
                ]
            ]);
    }

    /** @test */
    public function authenticated_user_cannot_view_other_users_subscription()
    {
        Passport::actingAs($this->user);
        
        $otherUser = User::factory()->create();
        $subscription = Subscription::factory()->create([
            'user_id' => $otherUser->id,
            'service_id' => $this->service->id
        ]);

        $response = $this->getJson("/api/v1/subscriptions/{$subscription->id}");

        $response->assertStatus(404);
    }

    /** @test */
    public function authenticated_user_can_update_subscription()
    {
        Passport::actingAs($this->user);
        
        $subscription = Subscription::factory()->create([
            'user_id' => $this->user->id,
            'service_id' => $this->service->id
        ]);

        $updateData = [
            'plan' => 'Basic',
            'price' => 19.99,
            'currency' => 'USD',
            'next_billing_date' => '2024-12-01',
            'status' => 'active'
        ];

        $response = $this->putJson("/api/v1/subscriptions/{$subscription->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data'
            ]);

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'plan' => 'Basic',
            'price' => 19.99
        ]);
    }

    /** @test */
    public function authenticated_user_can_delete_subscription()
    {
        Passport::actingAs($this->user);
        
        $subscription = Subscription::factory()->create([
            'user_id' => $this->user->id,
            'service_id' => $this->service->id
        ]);

        $response = $this->deleteJson("/api/v1/subscriptions/{$subscription->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message'
            ]);

        $this->assertDatabaseMissing('subscriptions', [
            'id' => $subscription->id
        ]);
    }

    /** @test */
    public function authenticated_user_can_cancel_subscription()
    {
        Passport::actingAs($this->user);
        
        $subscription = Subscription::factory()->create([
            'user_id' => $this->user->id,
            'service_id' => $this->service->id,
            'status' => 'active'
        ]);

        $response = $this->patchJson("/api/v1/subscriptions/{$subscription->id}/cancel");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data'
            ]);

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'status' => 'cancelled'
        ]);
    }

    /** @test */
    public function authenticated_user_can_reactivate_subscription()
    {
        Passport::actingAs($this->user);
        
        $subscription = Subscription::factory()->create([
            'user_id' => $this->user->id,
            'service_id' => $this->service->id,
            'status' => 'cancelled'
        ]);

        $response = $this->patchJson("/api/v1/subscriptions/{$subscription->id}/reactivate");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data'
            ]);

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'status' => 'active'
        ]);
    }

    /** @test */
    public function subscription_creation_requires_valid_data()
    {
        Passport::actingAs($this->user);

        $response = $this->postJson('/api/v1/subscriptions', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'service_id',
                'plan',
                'price',
                'currency',
                'next_billing_date',
                'status'
            ]);
    }

    /** @test */
    public function subscription_update_requires_valid_data()
    {
        Passport::actingAs($this->user);
        
        $subscription = Subscription::factory()->create([
            'user_id' => $this->user->id,
            'service_id' => $this->service->id
        ]);

        $response = $this->putJson("/api/v1/subscriptions/{$subscription->id}", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'plan',
                'price',
                'currency',
                'next_billing_date',
                'status'
            ]);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_subscription_endpoints()
    {
        $subscription = Subscription::factory()->create();

        // Test all endpoints without authentication
        $this->getJson('/api/v1/subscriptions')->assertStatus(401);
        $this->postJson('/api/v1/subscriptions', [])->assertStatus(401);
        $this->getJson("/api/v1/subscriptions/{$subscription->id}")->assertStatus(401);
        $this->putJson("/api/v1/subscriptions/{$subscription->id}", [])->assertStatus(401);
        $this->deleteJson("/api/v1/subscriptions/{$subscription->id}")->assertStatus(401);
        $this->patchJson("/api/v1/subscriptions/{$subscription->id}/cancel")->assertStatus(401);
        $this->patchJson("/api/v1/subscriptions/{$subscription->id}/reactivate")->assertStatus(401);
    }
}