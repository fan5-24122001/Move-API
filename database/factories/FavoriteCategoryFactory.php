<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FavoriteCategory>
 */
class FavoriteCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userId = User::inRandomOrder()->first();
        $categoryId = Category::inRandomOrder()->first();

        return [
            'user_id' => $userId->id,
            'category_id' => $categoryId->id,
        ];
    }
}
