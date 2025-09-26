<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserDataIsolationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_only_see_their_own_services()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $user1Service = Service::factory()->create([
            'name' => 'User 1 Netflix',
            'user_id' => $user1->id
        ]);
        
        $user2Service = Service::factory()->create([
            'name' => 'User 2 Spotify',
            'user_id' => $user2->id
        ]);

        $response = $this->actingAs($user1)->get('/services');
        
        $response->assertStatus(200)
            ->assertSee('User 1 Netflix')
            ->assertDontSee('User 2 Spotify');

        $response = $this->actingAs($user2)->get('/services');
        
        $response->assertStatus(200)
            ->assertSee('User 2 Spotify')
            ->assertDontSee('User 1 Netflix');
    }

    /** @test */
    public function user_can_only_see_their_own_subscriptions()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $user1Service = Service::factory()->create(['user_id' => $user1->id]);
        $user2Service = Service::factory()->create(['user_id' => $user2->id]);
        
        $user1Subscription = Subscription::create([
            'user_id' => $user1->id,
            'service_id' => $user1Service->id,
            'plan' => 'Basic Plan User 1',
            'price' => 9.99,
            'currency' => 'USD',
            'next_billing_date' => now()->addMonth(),
            'status' => 'active'
        ]);
        
        $user2Subscription = Subscription::create([
            'user_id' => $user2->id,
            'service_id' => $user2Service->id,
            'plan' => 'Premium Plan User 2',
            'price' => 19.99,
            'currency' => 'USD',
            'next_billing_date' => now()->addMonth(),
            'status' => 'active'
        ]);

        $response = $this->actingAs($user1)->get('/subscriptions');
        
        $response->assertStatus(200)
            ->assertSee('Basic Plan User 1')
            ->assertDontSee('Premium Plan User 2');

        $response = $this->actingAs($user2)->get('/subscriptions');
        
        $response->assertStatus(200)
            ->assertSee('Premium Plan User 2')
            ->assertDontSee('Basic Plan User 1');
    }

    /** @test */
    public function user_cannot_view_other_users_service_details()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $user1Service = Service::factory()->create(['user_id' => $user1->id]);
        $user2Service = Service::factory()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user1)->get("/services/{$user2Service->id}");
        $response->assertStatus(404);

        $response = $this->actingAs($user2)->get("/services/{$user1Service->id}");
        $response->assertStatus(404);

        $response = $this->actingAs($user1)->get("/services/{$user1Service->id}");
        $response->assertStatus(200);

        $response = $this->actingAs($user2)->get("/services/{$user2Service->id}");
        $response->assertStatus(200);
    }

    /** @test */
    public function user_cannot_view_other_users_subscription_details()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $user1Service = Service::factory()->create(['user_id' => $user1->id]);
        $user2Service = Service::factory()->create(['user_id' => $user2->id]);
        
        $user1Subscription = Subscription::create([
            'user_id' => $user1->id,
            'service_id' => $user1Service->id,
            'plan' => 'Basic',
            'price' => 9.99,
            'currency' => 'USD',
            'next_billing_date' => now()->addMonth(),
            'status' => 'active'
        ]);
        
        $user2Subscription = Subscription::create([
            'user_id' => $user2->id,
            'service_id' => $user2Service->id,
            'plan' => 'Premium',
            'price' => 19.99,
            'currency' => 'USD',
            'next_billing_date' => now()->addMonth(),
            'status' => 'active'
        ]);

        $response = $this->actingAs($user1)->get("/subscriptions/{$user2Subscription->id}");
        $response->assertStatus(404);

        $response = $this->actingAs($user2)->get("/subscriptions/{$user1Subscription->id}");
        $response->assertStatus(404);

        $response = $this->actingAs($user1)->get("/subscriptions/{$user1Subscription->id}");
        $response->assertStatus(200);

        $response = $this->actingAs($user2)->get("/subscriptions/{$user2Subscription->id}");
        $response->assertStatus(200);
    }

    /** @test */
    public function user_cannot_edit_other_users_services()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $user1Service = Service::factory()->create(['user_id' => $user1->id]);
        $user2Service = Service::factory()->create(['user_id' => $user2->id]);

        $updateData = [
            'name' => 'Hacked Service Name',
            'category' => 'Streaming',
            'description' => 'Malicious update'
        ];

        $response = $this->actingAs($user1)->put("/services/{$user2Service->id}", $updateData);
        $response->assertStatus(404);

        $response = $this->actingAs($user2)->put("/services/{$user1Service->id}", $updateData);
        $response->assertStatus(404);

        $this->assertDatabaseMissing('services', [
            'id' => $user1Service->id,
            'name' => 'Hacked Service Name'
        ]);
        
        $this->assertDatabaseMissing('services', [
            'id' => $user2Service->id,
            'name' => 'Hacked Service Name'
        ]);
    }

    /** @test */
    public function user_cannot_delete_other_users_services()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $user1Service = Service::factory()->create(['user_id' => $user1->id]);
        $user2Service = Service::factory()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user1)->delete("/services/{$user2Service->id}");
        $response->assertStatus(404);

        $response = $this->actingAs($user2)->delete("/services/{$user1Service->id}");
        $response->assertStatus(404);

        $this->assertDatabaseHas('services', ['id' => $user1Service->id]);
        $this->assertDatabaseHas('services', ['id' => $user2Service->id]);
    }

    /** @test */
    public function model_scopes_properly_filter_by_authenticated_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        Service::factory()->count(3)->create(['user_id' => $user1->id]);
        Service::factory()->count(2)->create(['user_id' => $user2->id]);
        
        $user1Services = Service::where('user_id', $user1->id)->get();
        $user2Services = Service::where('user_id', $user2->id)->get();
        
        foreach ($user1Services as $service) {
            Subscription::create([
                'user_id' => $user1->id,
                'service_id' => $service->id,
                'plan' => 'Basic',
                'price' => 9.99,
                'currency' => 'USD',
                'next_billing_date' => now()->addMonth(),
                'status' => 'active'
            ]);
        }
        
        foreach ($user2Services as $service) {
            Subscription::create([
                'user_id' => $user2->id,
                'service_id' => $service->id,
                'plan' => 'Premium',
                'price' => 19.99,
                'currency' => 'USD',
                'next_billing_date' => now()->addMonth(),
                'status' => 'active'
            ]);
        }

        $this->actingAs($user1);
        $user1ServicesCount = Service::forUser()->count();
        $this->assertEquals(3, $user1ServicesCount);
        
        $user1SubscriptionsCount = Subscription::forUser()->count();
        $this->assertEquals(3, $user1SubscriptionsCount);
        
        $this->actingAs($user2);
        $user2ServicesCount = Service::forUser()->count();
        $this->assertEquals(2, $user2ServicesCount);
        
        $user2SubscriptionsCount = Subscription::forUser()->count();
        $this->assertEquals(2, $user2SubscriptionsCount);
    }
}