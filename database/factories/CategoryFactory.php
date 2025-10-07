<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Oli Mesin Mobil',
            'Oli Mesin Motor',
            'Oli Transmisi',
            'Oli Gardan',
            'Filter Oli',
            'Filter Udara',
            'Filter Bensin',
            'Minyak Rem',
            'Aki Mobil',
            'Aki Motor',
            'Ban Mobil',
            'Ban Motor',
            'Spare Parts Mobil',
            'Spare Parts Motor',
            'Apparel & Merchandise'
        ];
        
        return [
            'name' => fake()->unique()->randomElement($categories),
        ];
    }
}
