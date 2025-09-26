<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_role_is_user_by_default()
    {
        $user = User::factory()->create();
        $this->assertEquals('user', $user->role);
    }

    public function test_can_create_admin_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->assertEquals('admin', $admin->role);
    }

    public function test_has_role_method_works()
    {
        $user = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);
        $this->assertTrue($user->hasRole('user'));
        $this->assertTrue($admin->hasRole('admin'));
        $this->assertFalse($user->hasRole('admin'));
        $this->assertFalse($admin->hasRole('user'));
    }
}
