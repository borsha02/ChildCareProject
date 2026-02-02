<?php

namespace Database\Factories;

use App\Models\Child;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'invoice_number' => 'INV-' . $this->faker->unique()->year . '-' . $this->faker->unique()->numberBetween(100, 999),
            'parent_id' => User::factory(), // Should ideally grab existing parent
            'child_id' => Child::factory(),   // Should ideally grab existing child
            'amount' => $this->faker->randomFloat(2, 50, 2000),
            'due_date' => $this->faker->dateTimeBetween('now', '+30 days'),
            'status' => $this->faker->randomElement(['paid', 'pending', 'overdue']),
        ];
    }
}
