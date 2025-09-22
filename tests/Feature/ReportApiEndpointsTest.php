<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

class ReportApiEndpointsTest extends TestCase
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
    public function authenticated_user_can_get_expense_report()
    {
        Passport::actingAs($this->user);
        
        // Create test subscriptions
        Subscription::factory()->create([
            'user_id' => $this->user->id,
            'service_id' => $this->service->id,
            'price' => 10.99,
            'status' => 'active'
        ]);
        
        Subscription::factory()->create([
            'user_id' => $this->user->id,
            'service_id' => $this->service->id,
            'price' => 5.99,
            'status' => 'cancelled'
        ]);

        $response = $this->getJson('/api/v1/reports/my-expenses');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user_name',
                    'total_subscriptions',
                    'total_expenses',
                    'currency',
                    'subscriptions' => [
                        '*' => [
                            'id',
                            'service_name',
                            'plan',
                            'price',
                            'currency',
                            'status',
                            'next_billing_date'
                        ]
                    ]
                ]
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'user_name' => $this->user->name,
                    'total_subscriptions' => 2,
                    'total_expenses' => 16.98
                ]
            ]);
    }

    /** @test */
    public function authenticated_user_can_filter_expense_report_by_status()
    {
        Passport::actingAs($this->user);
        
        // Create test subscriptions
        Subscription::factory()->create([
            'user_id' => $this->user->id,
            'service_id' => $this->service->id,
            'price' => 10.99,
            'status' => 'active'
        ]);
        
        Subscription::factory()->create([
            'user_id' => $this->user->id,
            'service_id' => $this->service->id,
            'price' => 5.99,
            'status' => 'cancelled'
        ]);

        $response = $this->getJson('/api/v1/reports/my-expenses?status=active');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'total_subscriptions' => 1,
                    'total_expenses' => 10.99
                ]
            ]);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_expense_report()
    {
        $response = $this->getJson('/api/v1/reports/my-expenses');

        $response->assertStatus(401);
    }

    /** @test */
    public function user_only_sees_their_own_subscriptions_in_report()
    {
        Passport::actingAs($this->user);
        
        // Create subscription for current user
        Subscription::factory()->create([
            'user_id' => $this->user->id,
            'service_id' => $this->service->id,
            'price' => 10.99
        ]);
        
        // Create subscription for another user
        $otherUser = User::factory()->create();
        $otherService = Service::factory()->create(['user_id' => $otherUser->id]);
        Subscription::factory()->create([
            'user_id' => $otherUser->id,
            'service_id' => $otherService->id,
            'price' => 20.99
        ]);

        $response = $this->getJson('/api/v1/reports/my-expenses');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'total_subscriptions' => 1,
                    'total_expenses' => 10.99
                ]
            ]);
    }
}