<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Service;
use App\Models\Subscription;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => bcrypt('AdminPassword@123'),
            'email_verified_at' => now(),
        ]);

        // USER
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'role' => 'user',
            'password' => bcrypt('UserPassword@123'),
            'email_verified_at' => now(),
        ]);

        // Create test data for USER
        $this->createTestDataForUser($user);
    }

    private function createTestDataForUser(User $user): void
    {
        // Create services
        $netflix = Service::create([
            'name' => 'Netflix',
            'description' => 'Streaming service',
            'category' => 'Entertainment',
            'user_id' => $user->id,
        ]);

        $spotify = Service::create([
            'name' => 'Spotify',
            'description' => 'Music streaming',
            'category' => 'Music',
            'user_id' => $user->id,
        ]);

        $adobe = Service::create([
            'name' => 'Adobe Creative Cloud',
            'description' => 'Design tools',
            'category' => 'Productivity',
            'user_id' => $user->id,
        ]);

        $github = Service::create([
            'name' => 'GitHub',
            'description' => 'Development platform',
            'category' => 'Development',
            'user_id' => $user->id,
        ]);

        // Create multiple subscriptions with diverse scenarios
        $subscriptions = [
            // Netflix - Multiple plans
            [
                'user_id' => $user->id,
                'service_id' => $netflix->id,
                'plan' => 'Basic',
                'price' => 8.99,
                'currency' => 'USD',
                'next_billing_date' => now()->addDays(15),
                'status' => 'active',
            ],
            [
                'user_id' => $user->id,
                'service_id' => $netflix->id,
                'plan' => 'Premium',
                'price' => 12.99,
                'currency' => 'EUR',
                'next_billing_date' => now()->addDays(5),
                'status' => 'cancelled',
            ],
            // Spotify - Different currencies and status
            [
                'user_id' => $user->id,
                'service_id' => $spotify->id,
                'plan' => 'Individual',
                'price' => 9.99,
                'currency' => 'USD',
                'next_billing_date' => now()->addDays(22),
                'status' => 'active',
            ],
            [
                'user_id' => $user->id,
                'service_id' => $spotify->id,
                'plan' => 'Family',
                'price' => 14.99,
                'currency' => 'USD',
                'next_billing_date' => now()->subDays(3),
                'status' => 'cancelled',
            ],
            [
                'user_id' => $user->id,
                'service_id' => $spotify->id,
                'plan' => 'Student',
                'price' => 4.99,
                'currency' => 'EUR',
                'next_billing_date' => now()->addDays(8),
                'status' => 'active',
            ],
            // Adobe - Annual and monthly
            [
                'user_id' => $user->id,
                'service_id' => $adobe->id,
                'plan' => 'All Apps Monthly',
                'price' => 52.99,
                'currency' => 'USD',
                'next_billing_date' => now()->addDays(12),
                'status' => 'active',
            ],
            [
                'user_id' => $user->id,
                'service_id' => $adobe->id,
                'plan' => 'Photography Annual',
                'price' => 119.88,
                'currency' => 'EUR',
                'next_billing_date' => now()->addDays(180),
                'status' => 'active',
            ],
            // GitHub - Different tiers
            [
                'user_id' => $user->id,
                'service_id' => $github->id,
                'plan' => 'Pro',
                'price' => 4.00,
                'currency' => 'USD',
                'next_billing_date' => now()->addDays(7),
                'status' => 'active',
            ],
            [
                'user_id' => $user->id,
                'service_id' => $github->id,
                'plan' => 'Team',
                'price' => 4.00,
                'currency' => 'EUR',
                'next_billing_date' => now()->addDays(25),
                'status' => 'cancelled',
            ],
        ];

        foreach ($subscriptions as $subscriptionData) {
            Subscription::create($subscriptionData);
        }
    }
}
