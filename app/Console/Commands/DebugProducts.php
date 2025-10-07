<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class DebugProducts extends Command
{
    protected $signature = 'debug:products';
    protected $description = 'Debug products data';

    public function handle()
    {
        $this->info('=== Debug Products ===');

        // Count total products
        $totalProducts = Product::count();
        $this->info("Total products: {$totalProducts}");

        // Count products with stock > 0
        $productsWithStock = Product::where('stock', '>', 0)->count();
        $this->info("Products with stock > 0: {$productsWithStock}");

        // Show first 5 products
        $this->info("\n=== First 5 Products ===");
        $products = Product::limit(5)->get();
        foreach ($products as $product) {
            $this->line("ID: {$product->id} | Name: {$product->name} | Stock: {$product->stock}");
        }

        // Test search for 'oli'
        $this->info("\n=== Search for 'oli' ===");
        $searchResults = Product::where('stock', '>', 0)
            ->where(function ($query) {
                $query->where('name', 'like', "%oli%")
                      ->orWhere('code', 'like', "%oli%");
            })
            ->get();

        $this->info("Found {$searchResults->count()} products with 'oli'");
        foreach ($searchResults as $product) {
            $this->line("- {$product->name} (Stock: {$product->stock})");
        }

        // Test case insensitive search
        $this->info("\n=== Case Insensitive Search for 'OLI' ===");
        $searchResults2 = Product::where('stock', '>', 0)
            ->where(function ($query) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower('OLI') . '%'])
                      ->orWhereRaw('LOWER(code) LIKE ?', ['%' . strtolower('OLI') . '%']);
            })
            ->get();

        $this->info("Found {$searchResults2->count()} products with 'OLI' (case insensitive)");
        foreach ($searchResults2 as $product) {
            $this->line("- {$product->name} (Stock: {$product->stock})");
        }

        return 0;
    }
}