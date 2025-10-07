<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'supplier'])->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'buy_price' => 'required|string',
            'sell_price' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Convert formatted prices to numbers
        $validated['buy_price'] = str_replace('.', '', $validated['buy_price']);
        $validated['sell_price'] = str_replace('.', '', $validated['sell_price']);

        // Generate kode produk otomatis
        $validated['code'] = $this->generateProductCode();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('product-images', 'public');
            $validated['image'] = $path;
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'buy_price' => 'required|string',
            'sell_price' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Convert formatted prices to numbers
        $validated['buy_price'] = str_replace('.', '', $validated['buy_price']);
        $validated['sell_price'] = str_replace('.', '', $validated['sell_price']);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('product-images', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }
    
    public function destroy(Product $product)
    {
        try {
            // With soft delete, we don't need to check foreign key constraint
            // The product will just be marked as deleted, not actually removed
            
            // Delete image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            // Soft delete the product
            $product->delete();
            
            return redirect()->route('products.index')
                ->with('success', 'Produk "' . $product->name . '" berhasil dihapus. Produk tetap tersimpan di riwayat transaksi.');
                
        } catch (\Exception $e) {
            return redirect()->route('products.index')
                ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    /**
     * Restock product
     */
    public function restock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:1',
        ]);

        // Tambahkan stok baru ke stok yang sudah ada
        $product->stock += $validated['stock'];
        $product->save();

        return response()->json([
            'success' => true,
            'message' => "Stok {$product->name} berhasil ditambah {$validated['stock']} unit. Total stok sekarang: {$product->stock}",
            'new_stock' => $product->stock
        ]);
    }

    /**
     * Generate unique product code
     */
    private function generateProductCode()
    {
        $prefix = 'PRD';
        $date = date('Ymd');
        
        // Cari produk terakhir yang dibuat hari ini (termasuk yang di-soft delete)
        $lastProduct = Product::withTrashed()
                             ->whereDate('created_at', today())
                             ->where('code', 'like', $prefix . $date . '%')
                             ->orderBy('code', 'desc')
                             ->first();
        
        if ($lastProduct) {
            // Ambil nomor urut terakhir
            $lastNumber = intval(substr($lastProduct->code, -3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        // Format: PRD + YYYYMMDD + 001
        $code = $prefix . $date . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        
        // Double check: pastikan kode benar-benar unik
        while (Product::withTrashed()->where('code', $code)->exists()) {
            $newNumber++;
            $code = $prefix . $date . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        }
        
        return $code;
    }
}