<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Point of Sale</h1>
                <p class="text-sm text-gray-500">Kelola penjualan dan transaksi</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-700 font-medium">{{ auth()->user()->name }}</div>
                <div class="text-xs text-gray-500">{{ now()->format('d M Y, H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6">
        
        <!-- Cart Section -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <!-- Cart Header -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-medium text-gray-900">Keranjang Belanja</h2>
                        <span class="text-sm text-gray-500">{{ count(session('cart', [])) }} items</span>
                    </div>
                </div>
                
                <!-- Cart Items -->
                <div class="p-6">
                    @if(session()->has('cart') && count(session('cart')) > 0)
                        <div class="space-y-4 mb-6">
                            @foreach(session('cart') as $item)
                                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900 text-sm">{{ $item['name'] }}</h4>
                                        <div class="flex items-center gap-2 mt-1">
                                            <input type="number" 
                                                   value="{{ $item['quantity'] }}" 
                                                   min="1" 
                                                   onchange="updateQuantityGlobal({{ $item['id'] }}, this.value)"
                                                   class="w-16 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-gray-500 focus:border-gray-500">
                                            <span class="text-xs text-gray-500">× Rp {{ number_format($item['price']) }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right ml-4">
                                        <div class="font-medium text-gray-900 text-sm">Rp {{ number_format($item['price'] * $item['quantity']) }}</div>
                                        <button onclick="removeFromCartGlobal({{ $item['id'] }})" 
                                                class="text-red-500 hover:text-red-700 text-xs mt-1">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Cart Total -->
                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-lg font-semibold text-gray-900">Total:</span>
                                <span id="grand-total" class="text-xl font-bold text-gray-900">
                                    Rp {{ number_format(collect(session('cart', []))->sum(fn($item) => $item['price'] * $item['quantity'])) }}
                                </span>
                            </div>
                            
                            <!-- Payment Input -->
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Bayar</label>
                                    <input type="number" 
                                           id="cash-input" 
                                           placeholder="0" 
                                           oninput="handlePaymentInput(this)"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                </div>
                                
                                <div id="change-display" class="hidden">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kembalian</label>
                                    <div id="change-amount" class="text-lg font-semibold text-green-600"></div>
                                </div>
                                
                                <button id="submit-transaction-btn" 
                                        onclick="submitTransactionGlobal()" 
                                        disabled
                                        class="w-full bg-gray-900 text-white py-3 px-4 rounded-lg font-medium hover:bg-gray-800 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors">
                                    Selesaikan Transaksi
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="mx-auto h-16 w-16 text-gray-300 mb-4">
                                <i class="bi bi-cart text-4xl"></i>
                            </div>
                            <p class="text-sm text-gray-500 font-medium">Keranjang kosong</p>
                            <p class="text-xs text-gray-400 mt-1">Cari dan tambahkan produk untuk memulai transaksi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Product Search Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <!-- Search Header -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Produk</label>
                            <input type="text" 
                                   id="search-input" 
                                   placeholder="Ketik nama atau kode produk..."
                                   oninput="doSearchGlobal(this.value)"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                        </div>
                    </div>
                </div>
                
                <!-- Search Results -->
                <div class="p-6">
                    <div id="search-loading" class="hidden text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-gray-900"></div>
                        <p class="text-sm text-gray-500 mt-2">Mencari produk...</p>
                    </div>
                    
                    <div id="search-results">
                        <div class="text-center py-12">
                            <div class="mx-auto h-16 w-16 text-gray-300 mb-4">
                                <i class="bi bi-search text-4xl"></i>
                            </div>
                            <p class="text-sm text-gray-500 font-medium">Mulai ketik untuk mencari produk</p>
                            <p class="text-xs text-gray-400 mt-1">Minimal 2 karakter</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update quantity function
window.updateQuantityGlobal = async function(productId, newQuantity) {
    if (newQuantity < 1) {
        showError('Quantity Error', 'Jumlah tidak boleh kurang dari 1');
        return;
    }
    
    try {
        const response = await fetch('/pos/update-quantity', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: newQuantity
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Calculate new total and update display
            updateTotalDisplay(data.cart_total);
            
            // Update change calculation
            const grandTotalText = document.getElementById('grand-total').textContent.replace(/[^0-9]/g, '');
            const grandTotal = parseInt(grandTotalText) || 0;
            updateChangeCalculation(grandTotal);
        } else {
            showError('Update Failed', data.message || 'Gagal mengupdate quantity');
        }
    } catch (error) {
        console.error('Update quantity error:', error);
        showError('Error Sistem', 'Terjadi kesalahan saat mengupdate quantity');
    }
};

// Remove from cart function
window.removeFromCartGlobal = async function(productId) {
    try {
        if (!confirm('Hapus produk ini dari keranjang?')) {
            return;
        }
        
        const response = await fetch('/pos/remove-from-cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
            },
            body: JSON.stringify({product_id: productId})
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccess('Produk Dihapus', data.message, 1500);
            setTimeout(() => window.location.reload(), 800);
        } else {
            showError('Gagal Menghapus', data.message || 'Gagal menghapus produk');
        }
    } catch (error) {
        console.error('Error removing from cart:', error);
        showError('Error Sistem', 'Terjadi kesalahan saat menghapus produk');
    }
};

// Submit transaction function
window.submitTransactionGlobal = async function() {
    const cashInput = document.getElementById('cash-input');
    const cashAmount = parseFloat(cashInput.value) || 0;
    
    if (cashAmount <= 0) {
        showError('Input Error', 'Masukkan jumlah pembayaran yang valid');
        return;
    }
    
    const grandTotalText = document.getElementById('grand-total').textContent.replace(/[^0-9]/g, '');
    const grandTotal = parseInt(grandTotalText) || 0;
    
    if (cashAmount < grandTotal) {
        showError('Pembayaran Kurang', 'Jumlah pembayaran kurang dari total belanja');
        return;
    }
    
    try {
        const response = await fetch('/pos/submit-transaction', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
            },
            body: JSON.stringify({cash: cashAmount})
        });
        const data = await response.json();
        
        if (data.success) {
            showSuccess(
                'Transaksi Berhasil!', 
                `Kembalian: Rp ${new Intl.NumberFormat('id-ID').format(data.change)}`,
                3000
            );
            setTimeout(() => window.location.reload(), 2000);
        } else {
            showError('Transaksi Gagal', data.message || 'Gagal menyelesaikan transaksi');
        }
    } catch (error) {
        console.error('Transaction error:', error);
        showError('Error Sistem', 'Terjadi kesalahan saat menyelesaikan transaksi. Silakan coba lagi.');
    }
};

// Search function
window.doSearchGlobal = async function(term) {
    const resultsDiv = document.getElementById('search-results');
    const loadingDiv = document.getElementById('search-loading');
    
    if (term.length < 2) {
        resultsDiv.innerHTML = `
            <div class="text-center py-12">
                <div class="mx-auto h-16 w-16 text-gray-300 mb-4">
                    <i class="bi bi-search text-4xl"></i>
                </div>
                <p class="text-sm text-gray-500 font-medium">Mulai ketik untuk mencari produk</p>
                <p class="text-xs text-gray-400 mt-1">Minimal 2 karakter</p>
            </div>
        `;
        return;
    }
    
    loadingDiv.classList.remove('hidden');
    
    try {
        const response = await fetch('/pos/search', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
            },
            body: JSON.stringify({search: term})
        });
        
        const data = await response.json();
        loadingDiv.classList.add('hidden');
        
        if (data.success && data.products.length > 0) {
            resultsDiv.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    ${data.products.map(product => `
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-medium text-gray-900 text-sm">${product.name}</h3>
                                <span class="text-xs text-gray-500">Stock: ${product.stock}</span>
                            </div>
                            <div class="text-lg font-semibold text-gray-900 mb-3">Rp ${new Intl.NumberFormat('id-ID').format(product.price)}</div>
                            <button onclick="addToCartGlobal(${product.id})" 
                                    ${product.stock <= 0 ? 'disabled' : ''}
                                    class="w-full py-2 px-3 text-sm font-medium rounded-lg transition-colors ${
                                        product.stock <= 0 
                                        ? 'bg-gray-100 text-gray-400 cursor-not-allowed' 
                                        : 'bg-gray-900 text-white hover:bg-gray-800'
                                    }">
                                ${product.stock <= 0 ? 'Stok Habis' : 'Tambah ke Keranjang'}
                            </button>
                        </div>
                    `).join('')}
                </div>
            `;
        } else {
            resultsDiv.innerHTML = `
                <div class="text-center py-12">
                    <div class="mx-auto h-16 w-16 text-gray-300 mb-4">
                        <i class="bi bi-search text-4xl"></i>
                    </div>
                    <p class="text-sm text-gray-500 font-medium">Produk tidak ditemukan</p>
                    <p class="text-xs text-gray-400 mt-1">Coba kata kunci lain</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Search error:', error);
        loadingDiv.classList.add('hidden');
        showError('Error Pencarian', 'Terjadi kesalahan saat mencari produk');
    }
};

// Add to cart function
window.addToCartGlobal = async function(productId) {
    try {
        const response = await fetch('/pos/add-to-cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
            },
            body: JSON.stringify({product_id: productId})
        });
        const data = await response.json();
        
        if (data.success) {
            showSuccess('Produk Ditambahkan', data.message, 1500);
            setTimeout(() => window.location.reload(), 800);
        } else {
            showError('Gagal Menambahkan', data.message || 'Gagal menambahkan ke keranjang');
        }
    } catch (error) {
        console.error('Add to cart error:', error);
        showError('Error Sistem', 'Terjadi kesalahan saat menambahkan ke keranjang');
    }
};

// Payment input handler
window.handlePaymentInput = function(input) {
    input.value = input.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
    
    const grandTotalElement = document.getElementById('grand-total');
    if (grandTotalElement) {
        const grandTotalText = grandTotalElement.textContent.replace(/[^0-9]/g, '');
        const grandTotal = parseInt(grandTotalText) || 0;
        updateChangeCalculation(grandTotal);
    }
};

// Update change calculation
function updateChangeCalculation(grandTotal) {
    const cashInput = document.getElementById('cash-input');
    const changeDisplay = document.getElementById('change-display');
    const changeAmount = document.getElementById('change-amount');
    const submitBtn = document.getElementById('submit-transaction-btn');
    
    const cashValue = parseFloat(cashInput.value) || 0;
    
    if (cashValue >= grandTotal && grandTotal > 0) {
        const change = cashValue - grandTotal;
        changeAmount.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(change)}`;
        changeDisplay.classList.remove('hidden');
        submitBtn.disabled = false;
    } else {
        changeDisplay.classList.add('hidden');
        submitBtn.disabled = true;
    }
}

// Update total display
function updateTotalDisplay(newTotal) {
    const grandTotalElement = document.getElementById('grand-total');
    if (grandTotalElement) {
        grandTotalElement.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(newTotal)}`;
    }
}
</script>