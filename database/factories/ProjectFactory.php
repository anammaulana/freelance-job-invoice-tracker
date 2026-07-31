<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-1 month', '+1 month');
        $deadline = fake()->dateTimeBetween($startDate, '+6 months');

        return [
            'client_id' => Client::factory(),
            'name' => fake()->catchPhrase(),
            'description' => fake()->paragraph(),
            'start_date' => $startDate->format('Y-m-d'),
            'deadline' => $deadline->format('Y-m-d'),
            'project_value' => fake()->randomFloat(2, 500, 50000),
            'status' => fake()->randomElement(Project::STATUSES),
        ];
    }
}
