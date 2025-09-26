<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PassportAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_access_protected_route()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->getJson('/api/v1/user');

        $response->assertStatus(200);
    }
}
