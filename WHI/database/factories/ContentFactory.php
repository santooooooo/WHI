<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Section;

class ContentFactory extends Factory
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
            'section_id' => Section::factory(),
            'type' => $this->faker->text(5),
            'substance' => $this->faker->text(),
        ];
    }
}
