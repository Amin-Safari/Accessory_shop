<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();
        return [
            'category_id' => fake()->numberBetween(1,10),
            'name' => $name,
            'images' => [$this->faker->imageUrl()],
            'slug' => $name,
            'description' => fake()->text(),
            'price' => fake()->numberBetween(100000, 1000000),
            'total' => fake()->numberBetween(1, 50),
            'is_new' => fake()->boolean(),
            'is_active' => fake()->boolean(),
            'discount' => fake()->numberBetween(0, 100),
        ];
    }
}
