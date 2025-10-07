<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Pos extends Component
{
    public $search = '';
    public $cart = [];
    public $paymentMethod = 'Cash';
    public $amountPaid = '';
    public $amountPaidFormatted = '';
    public $total = 0;
    public $searchResults;

    protected $listeners = ['productAdded' => 'refreshComponent'];

    public function mount()
    {
        $this->cart = session()->get('pos_cart', []);
        $this->calculateTotal();
        $this->searchResults = collect();
    }

    public function render()
    {
        // Always sync cart from session
        $this->cart = session()->get('pos_cart', []);
        $this->calculateTotal();
        
        // Debug cart state
        \Log::info('POS Render Debug', [
            'cart_items' => count($this->cart),
            'total' => $this->total,
            'session_cart' => session()->get('pos_cart', []),
        ]);
        
        return view('livewire.pos', [
            'products' => $this->searchResults ?? collect(),
        ]);
    }

    public function refreshCart()
    {
        $this->cart = session()->get('pos_cart', []);
        $this->calculateTotal();
        \Log::info('Cart Refreshed', ['cart_count' => count($this->cart), 'total' => $this->total]);
    }

    public function forceRefresh()
    {
        $this->cart = session()->get('pos_cart', []);
        $this->calculateTotal();
        return ['cart_count' => count($this->cart), 'total' => $this->total];
    }

    public function addToCart(Product $product)
    {
        $productId = $product->id;
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity']++;
        } else {
            if ($product->stock <= 0) {
                session()->flash('error', 'Stok produk habis!');
                return;
            }
            $this->cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->sell_price,
                'quantity' => 1,
            ];
        }
        $this->search = '';
        session()->put('pos_cart', $this->cart);
        session()->flash('success', 'Produk ' . $product->name . ' ditambahkan ke keranjang!');
    }

    public function updateCartQuantity($productId, $quantity)
    {
        if ($quantity < 1) {
            $this->removeFromCart($productId);
        } else {
            $product = Product::find($productId);
            if ($quantity > $product->stock) {
                $this->cart[$productId]['quantity'] = $product->stock;
                session()->flash('error', 'Kuantitas melebihi stok yang tersedia.');
            } else {
                $this->cart[$productId]['quantity'] = $quantity;
            }
        }
        session()->put('pos_cart', $this->cart);
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
        session()->put('pos_cart', $this->cart);
    }
    
    public function calculateTotal()
    {
        $this->total = 0;
        foreach ($this->cart as $item) {
            $this->total += $item['price'] * $item['quantity'];
        }
    }

    public function updatedAmountPaidFormatted()
    {
        // Convert formatted input (20.000) to number
        $this->amountPaid = str_replace('.', '', $this->amountPaidFormatted);
    }

    public function updatedPaymentMethod()
    {
        // Reset amount paid when payment method changes
        if ($this->paymentMethod !== 'Cash') {
            $this->amountPaid = $this->total;
            $this->amountPaidFormatted = number_format($this->total, 0, ',', '.');
        } else {
            $this->amountPaid = '';
            $this->amountPaidFormatted = '';
        }
    }

    public function submitTransaction()
    {
        // Sync cart from session first
        $this->cart = session()->get('pos_cart', []);
        $this->calculateTotal();
        
        // Validation rules based on payment method
        $rules = [
            'paymentMethod' => 'required',
        ];

        if ($this->paymentMethod === 'Cash') {
            $rules['amountPaid'] = 'required|numeric|min:' . $this->total;
        }

        $this->validate($rules);

        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang belanja kosong!');
            return;
        }

        // Set amount paid for non-cash payments
        if ($this->paymentMethod !== 'Cash') {
            $this->amountPaid = $this->total;
        }

        DB::transaction(function () {
            $transaction = Transaction::create([
                'invoice_number' => 'INV-' . date('Ymd') . '-' . mt_rand(1000, 9999),
                'user_id' => Auth::id(),
                'total_amount' => $this->total,
                'paid_amount' => $this->amountPaid,
                'change_amount' => $this->paymentMethod === 'Cash' ? max(0, $this->amountPaid - $this->total) : 0,
                'payment_method' => $this->paymentMethod,
            ]);

            foreach ($this->cart as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                $product = Product::find($item['id']);
                $product->decrement('stock', $item['quantity']);
            }
            
            // Clear both Livewire cart and session cart
            $this->reset(['cart', 'search', 'paymentMethod', 'amountPaid', 'amountPaidFormatted', 'total']);
            session()->forget('pos_cart');
            session()->flash('success', 'Transaksi berhasil disimpan! No Invoice: ' . $transaction->invoice_number);
        });
    }
}