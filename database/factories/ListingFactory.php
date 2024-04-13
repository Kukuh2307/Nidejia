<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Listing>
 */
class ListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "title" => ucwords(join(" ", $this->faker->words(2))),
            "description" => fake()->paragraph(),
            "address" => fake()->address(),
            "sqft" => fake()->randomNumber(1, 15),
            "wifi_speed" => fake()->numberBetween(1, 15),
            "max_person" => fake()->numberBetween(1, 8),
            "price_per_day" => fake()->numberBetween(1, 20),
            "full_support_available" => fake()->boolean(),
            "gym_area_available" => fake()->boolean(),
            "mini_cafe_available" => fake()->boolean(),
            "cinema_available" => fake()->boolean()
        ];
    }
}
