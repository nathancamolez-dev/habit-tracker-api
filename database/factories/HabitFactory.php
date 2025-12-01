<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Habit>
 */
class HabitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $habits = ['Fazer renda extra', 'Fazer compras', 'Fazer outros', 'Ser gentil'];

        return [
            'user_id' => User::factory(),
            'uuid'    => fake()->uuid(),
            'title'   => fake()->randomElement($habits),
        ];
    }
}
