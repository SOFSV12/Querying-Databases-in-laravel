<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number'  => fake()->numberBetween(1,10),
            'street'  => fake()->address(),
            'user_id' => fake()->unique($reset = true)->numberBetween(1,3)
        ];
    }
}
