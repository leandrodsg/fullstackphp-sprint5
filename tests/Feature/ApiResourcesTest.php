<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Service;
use App\Models\Subscription;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\SubscriptionResource;
use App\Http\Resources\UserResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiResourcesTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_resource_basic_fields(): void
    {
        $user = User::factory()->create();
        $service = Service::factory()->create([
            'user_id' => $user->id,
            'name' => 'Netflix',
            'category' => 'Streaming'
        ]);

        $resource = new ServiceResource($service);
        $array = $resource->toArray(request());

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('category', $array);
        $this->assertArrayHasKey('created_at', $array);
        $this->assertEquals('Netflix', $array['name']);
        $this->assertEquals('Streaming', $array['category']);
    }

    public function test_subscription_resource_basic_fields(): void
    {
        $user = User::factory()->create();
        $service = Service::factory()->create(['user_id' => $user->id]);
        
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'plan' => 'Premium',
            'price' => 15.99,
            'currency' => 'USD',
            'next_billing_date' => now()->addMonth(),
            'status' => 'active'
        ]);

        $resource = new SubscriptionResource($subscription);
        $array = $resource->toArray(request());

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('plan', $array);
        $this->assertArrayHasKey('price', $array);
        $this->assertArrayHasKey('currency', $array);
        $this->assertEquals('Premium', $array['plan']);
        $this->assertEquals(15.99, $array['price']);
        $this->assertEquals('USD', $array['currency']);
    }

    public function test_user_resource_safe_data_only(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]);

        $resource = new UserResource($user);
        $array = $resource->toArray(request());

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('email', $array);
        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
        
        $this->assertEquals('John Doe', $array['name']);
        $this->assertEquals('john@example.com', $array['email']);
    }
}