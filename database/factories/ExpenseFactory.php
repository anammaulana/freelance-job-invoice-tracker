<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'category' => fake()->randomElement(['Software', 'Travel', 'Equipment', 'Contractor']),
            'expense_date' => fake()->dateTimeBetween('-2 months', '+1 month')->format('Y-m-d'),
            'amount' => fake()->randomFloat(2, 10, 2500),
            'description' => fake()->sentence(),
            'vendor' => fake()->company(),
        ];
    }
}
