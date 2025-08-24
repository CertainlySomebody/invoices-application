<?php

namespace Database\Factories;

use App\Models\InvoiceProductLine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models>
 */
class InvoiceProductLinesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = InvoiceProductLine::class;

    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 20);
        $unitPrice = $this->faker->numberBetween(100, 1000);

        return [
            'name' => $this->faker->word(3, true),
            'quantity' => $quantity,
            'unit_price' => $unitPrice
        ];
    }
}
