<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Livewire\Pos;

class TestPosSearch extends Command
{
    protected $signature = 'test:pos-search {term}';
    protected $description = 'Test POS search functionality';

    public function handle()
    {
        $searchTerm = $this->argument('term');
        
        $this->info("Testing POS search with term: '{$searchTerm}'");
        
        // Create POS component instance
        $pos = new Pos();
        $pos->search = $searchTerm;
        
        // Render and get data
        $view = $pos->render();
        $products = $view->getData()['products'];
        
        $this->info("Found {$products->count()} products:");
        
        foreach ($products as $product) {
            $this->line("- {$product->name} (Stock: {$product->stock})");
        }
        
        return 0;
    }
}