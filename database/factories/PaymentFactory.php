<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'payment_date' => fake()->date(),
            'amount' => fake()->randomFloat(2, 50, 1000),
            'method' => fake()->randomElement(['Bank Transfer', 'Cash', 'Card']),
            'reference' => fake()->optional()->bothify('REF-####'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
