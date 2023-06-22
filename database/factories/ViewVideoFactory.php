<?php

namespace Database\Factories;

use App\Models\State;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ViewVideo>
 */
class ViewVideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $videoId = Video::inRandomOrder()->first();
        $userId = User::inRandomOrder()->first();
        $stateId = State::inRandomOrder()->first();

        return [
            'video_id' => $videoId->id,
            'user_id' => $userId->id,
            'age' => random_int(18,70),
            'gender' => random_int(0,2),
            'state_id' => $stateId->id,
            'country_id' => $stateId->country_id,
            'created_at' => Carbon::now()->subDays(365),
        ];
    }
}
