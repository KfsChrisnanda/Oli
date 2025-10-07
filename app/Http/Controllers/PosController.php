<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        return view('pos.index');
    }

    public function search(Request $request)
    {
        $searchTerm = strtolower($request->input('search', ''));
        
        \Log::info('POS Search Request:', [
            'search_term' => $searchTerm,
            'length' => strlen($searchTerm)
        ]);
        
        $products = collect();
        
        if (strlen($searchTerm) >= 2) {
            $products = Product::with(['category', 'supplier'])
                ->where('stock', '>', 0)
                ->where(function ($query) use ($searchTerm) {
                    $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"])
                          ->orWhereRaw('LOWER(code) LIKE ?', ["%{$searchTerm}%"]);
                })
                ->orderBy('name', 'asc')
                ->limit(10)
                ->get();
        }

        $response = [
            'products' => $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->code,
                    'stock' => $product->stock,
                    'minimum_stock' => $product->minimum_stock,
                    'sell_price' => $product->sell_price,
                    'image' => $product->image,
                    'category' => $product->category ? ['name' => $product->category->name] : null,
                ];
            }),
            'count' => $products->count(),
            'search_term' => $searchTerm
        ];

        \Log::info('POS Search Response:', $response);

        return response()->json($response);
    }

    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $product = Product::find($productId);
        
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan']);
        }
        
        if ($product->stock <= 0) {
            return response()->json(['success' => false, 'message' => 'Stok produk habis']);
        }
        
        // Get cart from session
        $cart = session()->get('pos_cart', []);
        
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->sell_price,
                'quantity' => 1,
            ];
        }
        
        // Save cart to session
        session()->put('pos_cart', $cart);
        
        return response()->json([
            'success' => true, 
            'message' => 'Produk ditambahkan ke keranjang',
            'cart' => $cart,
            'cart_count' => array_sum(array_column($cart, 'quantity'))
        ]);
    }

    public function removeFromCart(Request $request)
    {
        try {
            $productId = $request->input('product_id');
            
            // Get cart from session
            $cart = session()->get('pos_cart', []);
            
            if (isset($cart[$productId])) {
                // Remove product from cart
                unset($cart[$productId]);
                
                // Save updated cart to session
                session()->put('pos_cart', $cart);
                
                // Calculate new totals
                $cartItems = collect($cart);
                $totalQuantity = $cartItems->sum('quantity');
                $grandTotal = $cartItems->sum(function ($item) {
                    return $item['price'] * $item['quantity'];
                });
                
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil dihapus dari keranjang',
                    'cart' => $cart,
                    'total_quantity' => $totalQuantity,
                    'grand_total' => $grandTotal
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan dalam keranjang'
                ]);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus produk: ' . $e->getMessage()
            ]);
        }
    }

    public function updateQuantity(Request $request)
    {
        try {
            $productId = $request->input('product_id');
            $quantity = max(1, intval($request->input('quantity')));
            
            // Get cart from session
            $cart = session()->get('pos_cart', []);
            
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = $quantity;
                
                // Save updated cart to session
                session()->put('pos_cart', $cart);
                
                // Calculate new totals
                $cartItems = collect($cart);
                $totalQuantity = $cartItems->sum('quantity');
                $grandTotal = $cartItems->sum(function ($item) {
                    return $item['price'] * $item['quantity'];
                });
                
                return response()->json([
                    'success' => true,
                    'message' => 'Quantity updated successfully',
                    'cart' => $cart,
                    'total_quantity' => $totalQuantity,
                    'grand_total' => $grandTotal
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found in cart'
                ]);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update quantity: ' . $e->getMessage()
            ]);
        }
    }

    public function submitTransaction(Request $request)
    {
        try {
            // Parse cash amount - remove all non-numeric characters except decimal point
            $cashInput = $request->input('cash', 0);
            $cash = floatval(preg_replace('/[^0-9.]/', '', $cashInput));
            
            $cart = session()->get('pos_cart', []);
            
            if (empty($cart)) {
                return response()->json(['success' => false, 'message' => 'Keranjang kosong']);
            }

            // Calculate grand total
            $grandTotal = collect($cart)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });

            // Check if cash is sufficient
            if ($cash < $grandTotal) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Uang tidak cukup. Dibayar: Rp ' . number_format($cash) . ', Total: Rp ' . number_format($grandTotal)
                ]);
            }

            DB::beginTransaction();

            // Create transaction
            $transaction = Transaction::create([
                'invoice_number' => 'INV-' . now()->format('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'user_id' => auth()->id(),
                'total_amount' => $grandTotal,
                'paid_amount' => $cash,
                'change_amount' => $cash - $grandTotal,
                'payment_method' => 'Cash'
            ]);

            // Create transaction details
            foreach ($cart as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity']
                ]);

                // Update product stock
                $product = Product::find($item['id']);
                if ($product) {
                    $product->stock -= $item['quantity'];
                    $product->save();
                }
            }

            DB::commit();
            
            // Clear cart
            session()->forget('pos_cart');

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diselesaikan',
                'transaction_id' => $transaction->id,
                'total' => $grandTotal,
                'change' => $cash - $grandTotal
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Transaksi gagal: ' . $e->getMessage()]);
        }
    }
}