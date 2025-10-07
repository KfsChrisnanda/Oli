<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; // <-- PASTIKAN BARIS INI ADA

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat User dengan role yang berbeda
        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        // Buat User Kasir
        $kasirUser = User::factory()->create([
            'name' => 'Kasir User',
            'email' => 'kasir@example.com',
            'role' => 'kasir',
        ]);

        // Buat User Penjaga Gudang
        $gudangUser = User::factory()->create([
            'name' => 'Penjaga Gudang',
            'email' => 'gudang@example.com',
            'role' => 'penjaga_gudang',
        ]);

        // Buat beberapa user tambahan dengan role kasir
        User::factory(3)->create([
            'role' => 'kasir',
        ]);

        // Buat beberapa user penjaga gudang tambahan
        User::factory(2)->create([
            'role' => 'penjaga_gudang',
        ]);

        // Buat data dummy untuk Supplier (15 supplier)
        Supplier::factory(15)->create();
        
        // Buat kategori spesifik yang realistis
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
        
        foreach ($categories as $categoryName) {
            Category::firstOrCreate(['name' => $categoryName]);
        }
        
        // Buat 50 produk untuk testing yang lebih komprehensif
        Product::factory(50)->create();
        
        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin login: admin@example.com / password');
        $this->command->info('Kasir login: kasir@example.com / password');
    }
}