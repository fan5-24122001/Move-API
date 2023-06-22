<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rate>
 */
class RateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userID = User::inRandomOrder()->first();
        $videoID = Video::inRandomOrder()->first();

        return [
            'user_id' => $userID->id,
            'video_id' => $videoID->id,
            'rate' => fake()->numberBetween(1, 5),
        ];
    }
}
