<?php


namespace Tests\Feature;


use App\Rules\StrongPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StrongPasswordTest extends TestCase
{
    use RefreshDatabase;
    public function test_password_requires_minimum_length(): void
    {
        $rule = new StrongPassword();
        $fails = false;
        
        $rule->validate('password', '123456789', function() use (&$fails) {
            $fails = true;
        });
        
        $this->assertTrue($fails, 'Password should fail with less than 10 characters');
    }

    public function test_password_requires_lowercase_letter(): void
    {
        $rule = new StrongPassword();
        $fails = false;
        
        $rule->validate('password', 'PASSWORD123@', function() use (&$fails) {
            $fails = true;
        });
        
        $this->assertTrue($fails, 'Password should fail without lowercase letter');
    }

    public function test_password_requires_uppercase_letter(): void
    {
        $rule = new StrongPassword();
        $fails = false;
        
        $rule->validate('password', 'password123@', function() use (&$fails) {
            $fails = true;
        });
        
        $this->assertTrue($fails, 'Password should fail without uppercase letter');
    }

    public function test_password_requires_number(): void
    {
        $rule = new StrongPassword();
        $fails = false;
        
        $rule->validate('password', 'PasswordTest@', function() use (&$fails) {
            $fails = true;
        });
        
        $this->assertTrue($fails, 'Password should fail without number');
    }

    public function test_password_requires_special_character(): void
    {
        $rule = new StrongPassword();
        $fails = false;
        
        $rule->validate('password', 'PasswordTest123', function() use (&$fails) {
            $fails = true;
        });
        
        $this->assertTrue($fails, 'Password should fail without special character');
    }

    public function test_valid_strong_password_passes(): void
    {
        $rule = new StrongPassword();
        $fails = false;
        
            $rule->validate('password', 'MyPassword123@', function() use (&$fails) {
            $fails = true;
        });
        
        $this->assertFalse($fails, 'Valid strong password should pass all checks');
    }

    public function test_registration_with_weak_password_fails(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'weak',
            'password_confirmation' => 'weak'
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_registration_with_strong_password_succeeds(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com', 
                'password' => 'StrongPassword123@',
                'password_confirmation' => 'StrongPassword123@'
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com'
        ]);
    }
}