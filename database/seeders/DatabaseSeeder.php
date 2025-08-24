<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use App\Models\Subscription;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create services
        $services = [
            [
                'name' => 'Netflix',
                'category' => 'streaming',
                'website_url' => 'https://www.netflix.com',
                'description' => 'Streaming platform for movies and series'
            ],
            [
                'name' => 'Spotify',
                'category' => 'music',
                'website_url' => 'https://www.spotify.com',
                'description' => 'Music streaming service'
            ],
            [
                'name' => 'Adobe Creative Cloud',
                'category' => 'software',
                'website_url' => 'https://www.adobe.com',
                'description' => 'Creative software suite'
            ],
            [
                'name' => 'GitHub Pro',
                'category' => 'development',
                'website_url' => 'https://github.com',
                'description' => 'Code repository and collaboration platform'
            ],
            [
                'name' => 'Dropbox',
                'category' => 'storage',
                'website_url' => 'https://www.dropbox.com',
                'description' => 'Cloud storage service'
            ],
            [
                'name' => 'Canva Pro',
                'category' => 'design',
                'website_url' => 'https://www.canva.com',
                'description' => 'Online design and publishing tool'
            ]
        ];

        foreach ($services as $serviceData) {
            Service::create($serviceData);
        }

        // Create subscriptions for the test user
        $subscriptions = [
            [
                'user_id' => $user->id,
                'service_id' => 1, // Netflix
                'plan' => 'Premium',
                'price' => 15.99,
                'currency' => 'USD',
                'next_billing_date' => now()->addMonth(),
                'status' => 'active'
            ],
            [
                'user_id' => $user->id,
                'service_id' => 2, // Spotify
                'plan' => 'Individual',
                'price' => 9.99,
                'currency' => 'USD',
                'next_billing_date' => now()->addMonth(),
                'status' => 'active'
            ],
            [
                'user_id' => $user->id,
                'service_id' => 3, // Adobe
                'plan' => 'All Apps',
                'price' => 52.99,
                'currency' => 'USD',
                'next_billing_date' => now()->addMonth(),
                'status' => 'active'
            ],
            [
                'user_id' => $user->id,
                'service_id' => 4, // GitHub
                'plan' => 'Pro',
                'price' => 4.00,
                'currency' => 'USD',
                'next_billing_date' => now()->addMonth(),
                'status' => 'cancelled'
            ]
        ];

        foreach ($subscriptions as $subscriptionData) {
            Subscription::create($subscriptionData);
        }
    }
}
