<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->name(),
            'tag' => 'HIIT, MMA',
            'thumbnail' => fake()->imageUrl(),
            'url_video' => '/videos/833174274',
            'user_id' => User::all()->random()->id,
            'category_id' => Category::all()->random()->id,
            'level' => fake()->numberBetween(1, 3),
            'duration' => fake()->numberBetween(1, 3),
            'status' => fake()->numberBetween(0, 1),
            'commentable' => fake()->numberBetween(0, 1),
        ];
    }
}
