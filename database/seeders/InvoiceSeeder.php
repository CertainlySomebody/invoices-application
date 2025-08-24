<?php

namespace Database\Seeders;


use App\Models\Invoice;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Invoice::factory()
            ->count(30)
            ->state(fn () => ['status' => 'draft'])
            ->create()
            ->each(function (Invoice $invoice) {
                $lines = fake()->numberBetween(2, 6);
                $invoice->productLines()->createMany(
                    collect(range(1,$lines))->map(function() {
                        $q = fake()->numberBetween(1, 10);
                        $p = fake()->numberBetween(100, 5000);
                        return [
                            'name' => fake()->words(3, true),
                            'price' => $p,
                            'quantity' => $q,
                        ];
                    })->all()
                );
            });
    }
}
