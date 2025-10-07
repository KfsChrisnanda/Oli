<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $companies = [
            'PT. Shell Indonesia',
            'PT. Castrol Indonesia',
            'PT. Top 1 Indonesia',
            'PT. Pertamina Lubricants',
            'PT. Mobil Oil Indonesia',
            'PT. Valvoline Indonesia',
            'PT. Total Oil Indonesia',
            'PT. Motul Indonesia',
            'PT. Liqui Moly Indonesia',
            'PT. Gulf Oil Indonesia'
        ];
        
        return [
            'name' => fake()->randomElement($companies),
            'phone' => fake()->unique()->numerify('08##-####-####'),
            'address' => fake()->streetAddress() . ', ' . fake()->city() . ', ' . fake()->state(),
        ];
    }
}
