<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File; // <-- TAMBAHKAN INI

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $buyPrice = fake()->numberBetween(50000, 500000);
        $sellPrice = round($buyPrice * fake()->randomFloat(2, 1.2, 1.5)); // Profit 20-50%

        // Data produk oli yang realistis
        $oilProducts = [
            'Shell Helix Ultra',
            'Castrol GTX',
            'Top 1 Super Synthetic',
            'Pertamina Prima XP',
            'Mobil 1 ESP',
            'Valvoline MaxLife',
            'Total Quartz',
            'Motul 8100',
            'Liqui Moly Synthoil',
            'Gulf Formula G'
        ];
        
        $viscosities = ['5W-30', '10W-40', '15W-40', '20W-50', 'SAE 40', 'SAE 50'];
        
        // --- LOGIKA UNTUK GAMBAR DUMMY ---
        $imagePath = storage_path('app/public/product-images');
        
        if (!File::isDirectory($imagePath)) {
            File::makeDirectory($imagePath, 0755, true, true);
        }
        
        // Buat gambar dummy dengan ukuran yang konsisten
        $imageFileName = fake()->image($imagePath, 400, 400, 'business', false);
        // --- SELESAI ---

        return [
            'name' => fake()->randomElement($oilProducts) . ' ' . fake()->randomElement($viscosities) . ' ' . fake()->randomElement(['1L', '4L', '5L']),
            'code' => fake()->unique()->bothify('PROD-#####'),
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'supplier_id' => Supplier::inRandomOrder()->first()?->id ?? Supplier::factory(),
            'image' => 'product-images/' . $imageFileName,
            'stock' => fake()->numberBetween(10, 150),
            'minimum_stock' => fake()->numberBetween(5, 20),
            'buy_price' => $buyPrice,
            'sell_price' => $sellPrice,
        ];
    }
}