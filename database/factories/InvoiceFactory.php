<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        $issueDate = fake()->dateTimeBetween('-1 month', '+1 month');
        $dueDate = fake()->dateTimeBetween($issueDate, '+2 months');

        return [
            'project_id' => Project::factory(),
            'invoice_number' => 'INV-'.$issueDate->format('Ym').'-'.fake()->unique()->numerify('####'),
            'issue_date' => $issueDate->format('Y-m-d'),
            'due_date' => $dueDate->format('Y-m-d'),
            'amount' => fake()->randomFloat(2, 100, 5000),
            'notes' => fake()->optional()->sentence(),
            'status' => fake()->randomElement(Invoice::STATUSES),
        ];
    }
}
