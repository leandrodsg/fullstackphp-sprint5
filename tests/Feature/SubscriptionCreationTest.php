<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriptionCreationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_create_monthly_subscription()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create(['user_id' => $user->id]);
        
        $monthlySubscriptionData = [
            'service_id' => $service->id,
            'plan' => 'Basic',
            'price' => 9.99,
            'currency' => 'USD',
            'next_billing_date' => now()->addMonth()->format('Y-m-d'),
            'status' => 'active'
        ];

        $response = $this->actingAs($user)
            ->post('/subscriptions', $monthlySubscriptionData);

        $response->assertRedirect('/subscriptions')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('subscriptions', [
            'service_id' => $service->id,
            'plan' => 'Basic',
            'price' => 9.99,
            'currency' => 'USD',
            'status' => 'active',
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function authenticated_user_can_create_annual_subscription()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create(['user_id' => $user->id]);
        
        $annualSubscriptionData = [
            'service_id' => $service->id,
            'plan' => 'Premium Annual',
            'price' => 99.99,
            'currency' => 'USD',
            'next_billing_date' => now()->addYear()->format('Y-m-d'),
            'status' => 'active'
        ];

        $response = $this->actingAs($user)
            ->post('/subscriptions', $annualSubscriptionData);

        $response->assertRedirect('/subscriptions')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('subscriptions', [
            'service_id' => $service->id,
            'plan' => 'Premium Annual',
            'price' => 99.99,
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function subscription_creation_fails_with_invalid_service()
    {
        $user = User::factory()->create();
        
        $invalidServiceData = [
            'service_id' => 999, // Non-existent service
            'plan' => 'Basic',
            'price' => 9.99,
            'currency' => 'USD',
            'next_billing_date' => now()->addMonth()->format('Y-m-d'),
            'status' => 'active'
        ];

        $response = $this->actingAs($user)
            ->post('/subscriptions', $invalidServiceData);

        $response->assertSessionHasErrors(['service_id']);
        $this->assertDatabaseCount('subscriptions', 0);
    }

    /** @test */
    public function subscription_creation_fails_with_missing_required_fields()
    {
        $user = User::factory()->create();
        
        $incompleteData = [
            'plan' => 'Basic'
            // Missing service_id, price, currency, next_billing_date
        ];

        $response = $this->actingAs($user)
            ->post('/subscriptions', $incompleteData);

        $response->assertSessionHasErrors(['service_id', 'price', 'currency', 'next_billing_date']);
        $this->assertDatabaseCount('subscriptions', 0);
    }

    /** @test */
    public function subscription_creation_fails_with_invalid_currency()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create(['user_id' => $user->id]);
        
        $invalidCurrencyData = [
            'service_id' => $service->id,
            'plan' => 'Basic',
            'price' => 9.99,
            'currency' => 'BRL', // Invalid currency (only USD, EUR allowed)
            'next_billing_date' => now()->addMonth()->format('Y-m-d'),
            'status' => 'active'
        ];

        $response = $this->actingAs($user)
            ->post('/subscriptions', $invalidCurrencyData);

        $response->assertSessionHasErrors(['currency']);
        $this->assertDatabaseCount('subscriptions', 0);
    }

    /** @test */
    public function subscription_creation_fails_with_invalid_price()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create(['user_id' => $user->id]);
        
        $invalidPriceData = [
            'service_id' => $service->id,
            'plan' => 'Basic',
            'price' => 0.00, // Invalid price (minimum is 0.01)
            'currency' => 'USD',
            'next_billing_date' => now()->addMonth()->format('Y-m-d'),
            'status' => 'active'
        ];

        $response = $this->actingAs($user)
            ->post('/subscriptions', $invalidPriceData);

        $response->assertSessionHasErrors(['price']);
        $this->assertDatabaseCount('subscriptions', 0);
    }

    /** @test */
    public function user_cannot_create_subscription_for_other_users_service()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $otherUserService = Service::factory()->create(['user_id' => $user1->id]);
        
        $subscriptionData = [
            'service_id' => $otherUserService->id,
            'plan' => 'Basic',
            'price' => 9.99,
            'currency' => 'USD',
            'next_billing_date' => now()->addMonth()->format('Y-m-d'),
            'status' => 'active'
        ];

        $response = $this->actingAs($user2)
            ->post('/subscriptions', $subscriptionData);

        $response->assertSessionHasErrors(['service_id']);
        $this->assertDatabaseCount('subscriptions', 0);
    }

    /** @test */
    public function unauthenticated_user_cannot_create_subscription()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create(['user_id' => $user->id]);
        
        $subscriptionData = [
            'service_id' => $service->id,
            'plan' => 'Basic',
            'price' => 9.99,
            'currency' => 'USD',
            'next_billing_date' => now()->addMonth()->format('Y-m-d'),
            'status' => 'active'
        ];

        $response = $this->post('/subscriptions', $subscriptionData);

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('subscriptions', 0);
    }
}