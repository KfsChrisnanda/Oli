<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Product;

class CheckLowStock
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check for authenticated users with proper permissions
        if (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isPenjagaGudang())) {
            
            // Get low stock products (stock <= minimum_stock for each product)
            $lowStockProducts = Product::whereColumn('stock', '<=', 'minimum_stock')
                ->where('stock', '>', 0)
                ->get();
            
            if ($lowStockProducts->count() > 0) {
                // Check if we haven't shown this alert in the last 30 minutes
                $lastAlert = session('last_low_stock_alert', 0);
                $now = now()->timestamp;
                
                if ($now - $lastAlert > 1800) { // 30 minutes = 1800 seconds
                    $productNames = $lowStockProducts->pluck('name')->take(3)->implode(', ');
                    $count = $lowStockProducts->count();
                    
                    if ($count > 3) {
                        $message = "⚠️ {$count} produk memiliki stok menipis: {$productNames} dan " . ($count - 3) . " produk lainnya";
                    } else {
                        $message = "⚠️ Stok menipis: {$productNames}";
                    }
                    
                    session()->flash('low_stock_alert', $message);
                    session(['last_low_stock_alert' => $now]);
                }
            }
        }

        return $next($request);
    }
}
