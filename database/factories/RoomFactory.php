<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'room_number' => fake()->unique()->numberBetween(1,20),
            'room_size'   => fake()->randomNumber(2, true),
            'price'       => fake()->randomNumber(3, true),  
            'description' => fake()->paragraph(),
        ];
    }
}
