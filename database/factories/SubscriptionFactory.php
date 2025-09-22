<?php

namespace Database\Factories;

use App\Models\Subscription;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition(): array
    {
        return [
            'service_id' => 1,
            'plan' => $this->faker->randomElement(['Basic', 'Premium', 'Pro']),
            'price' => $this->faker->randomFloat(2, 5, 100),
            'currency' => 'USD',
            'next_billing_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'status' => 'active',
            'user_id' => 1,
        ];
    }
}