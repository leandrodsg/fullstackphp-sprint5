<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company(),
            'category' => 'Streaming',
            'website_url' => 'https://example.com',
            'description' => $this->faker->sentence(),
            'user_id' => null,
        ];
    }
}
