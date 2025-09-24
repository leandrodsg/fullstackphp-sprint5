<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Service;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DomainModelMethodsTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_for_user_scope(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $service1 = Service::factory()->create(['user_id' => $user1->id, 'name' => 'User1 Service']);
        $service2 = Service::factory()->create(['user_id' => $user2->id, 'name' => 'User2 Service']);

        $user1Services = Service::forUser($user1->id)->get();
        $this->assertCount(1, $user1Services);
        $this->assertEquals('User1 Service', $user1Services->first()->name);

        $this->actingAs($user2);
        $user2Services = Service::forUser()->get();
        $this->assertCount(1, $user2Services);
        $this->assertEquals('User2 Service', $user2Services->first()->name);
    }

    public function test_subscription_for_user_scope(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $service1 = Service::factory()->create(['user_id' => $user1->id]);
        $service2 = Service::factory()->create(['user_id' => $user2->id]);
        
        $subscription1 = Subscription::create([
            'user_id' => $user1->id,
            'service_id' => $service1->id,
            'plan' => 'Basic',
            'price' => 10.00,
            'currency' => 'USD',
            'next_billing_date' => now()->addDays(10),
            'status' => 'active'
        ]);

        $subscription2 = Subscription::create([
            'user_id' => $user2->id,
            'service_id' => $service2->id,
            'plan' => 'Premium',
            'price' => 20.00,
            'currency' => 'USD',
            'next_billing_date' => now()->addDays(15),
            'status' => 'active'
        ]);

        $user1Subscriptions = Subscription::forUser($user1->id)->get();
        $this->assertCount(1, $user1Subscriptions);
        $this->assertEquals('Basic', $user1Subscriptions->first()->plan);

        $this->actingAs($user2);
        $user2Subscriptions = Subscription::forUser()->get();
        $this->assertCount(1, $user2Subscriptions);
        $this->assertEquals('Premium', $user2Subscriptions->first()->plan);
    }

    public function test_subscription_is_due_method(): void
    {
        $user = User::factory()->create();
        $service = Service::factory()->create(['user_id' => $user->id]);

        $dueSubscription = Subscription::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'plan' => 'Basic',
            'price' => 10.00,
            'currency' => 'USD',
            'next_billing_date' => now()->subDay(),
            'status' => 'active'
        ]);

        $notDueSubscription = Subscription::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'plan' => 'Premium',
            'price' => 20.00,
            'currency' => 'USD',
            'next_billing_date' => now()->addMonth(),
            'status' => 'active'
        ]);

        $this->assertTrue($dueSubscription->isDue());
        $this->assertFalse($notDueSubscription->isDue());
    }

    public function test_subscription_advance_billing_date(): void
    {
        $user = User::factory()->create();
        $service = Service::factory()->create(['user_id' => $user->id]);

        $originalDate = now()->toDateString();
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'plan' => 'Basic',
            'price' => 10.00,
            'currency' => 'USD',
            'next_billing_date' => $originalDate,
            'status' => 'active'
        ]);

        $subscription->advanceBillingDate();
        
        $subscription->refresh();
        $expectedDate = now()->addMonth()->toDateString();
        $this->assertEquals($expectedDate, $subscription->next_billing_date->toDateString());
    }
}