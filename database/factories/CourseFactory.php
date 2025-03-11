<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'duration' => $this->faker->randomElement([5, 10, 20]),
            'difficulty_level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'subcategory_id' => Category::inRandomOrder()->first()->id ?? null,
            'status' => $this->faker->randomElement(['open', 'in_progress', 'completed']),
        ];
    }
}
