<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Invoices\Domain\ValueObjects\InvoiceProductLine;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->unique()->safeEmail(),
            'status' => 'draft'
        ];
    }
}
