<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuctionFactory extends Factory {
    public function definition(): array {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'image_url' => 'https://picsum.photos/seed/'.rand(1,999).'/1200/800',
            'starting_price' => 1000,
            'current_price' => 1000,
            'ends_at' => now()->addDay()
        ];
    }
}
