<?php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RefactorFormRequestsTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_creation_requires_required_fields(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/services', []);
        $response->assertSessionHasErrors(['name','category']);
    }

    public function test_service_creation_success(): void
    {
        $user = User::factory()->create();
        $payload = [
            'name' => 'Netflix',
            'category' => 'Streaming',
            'description' => 'Video service',
            'website_url' => 'https://netflix.com'
        ];
        $response = $this->actingAs($user)->post('/services', $payload);
        $response->assertRedirect(route('services.index'));
        $this->assertDatabaseHas('services', [
            'name' => 'Netflix',
            'category' => 'Streaming',
            'user_id' => $user->id
        ]);
    }

    public function test_subscription_creation_requires_required_fields(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/subscriptions', []);
        $response->assertSessionHasErrors(['service_id','plan','price','currency','next_billing_date','status']);
    }

    public function test_subscription_creation_success(): void
    {
        $user = User::factory()->create();
        $service = Service::factory()->create(['user_id' => $user->id]);
        $payload = [
            'service_id' => $service->id,
            'plan' => 'Standard',
            'price' => 15.99,
            'currency' => 'USD',
            'next_billing_date' => now()->addMonth()->toDateString(),
            'status' => 'active'
        ];
        $response = $this->actingAs($user)->post('/subscriptions', $payload);
        $response->assertRedirect(route('subscriptions.index'));
        $this->assertDatabaseHas('subscriptions', [
            'service_id' => $service->id,
            'plan' => 'Standard',
            'user_id' => $user->id
        ]);
    }
}
