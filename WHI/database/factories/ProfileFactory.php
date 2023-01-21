<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Str;

class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'icon' => $this->faker->imageUrl(),
            'career' => $this->faker->text(),
            'title' => $this->faker->text(),
            'text' => $this->faker->text(),
            'mail' => $this->faker->email(),
            'twitter' => $this->faker->text(),
        ];
    }
}
