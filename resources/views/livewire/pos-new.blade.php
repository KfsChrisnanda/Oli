<div class="min-h-screen">
    <div class="space-y-4">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Point of Sale</h1>
                    <p class="text-purple-100 text-sm mt-1">Kelola penjualan dan transaksi</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-purple-100">Kasir: {{ auth()->user()->name }}</div>
                    <div class="text-xs text-purple-200">{{ now()->format('d M Y, H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- Main POS Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            
            <!-- Cart Section (Left - 2 columns) -->
            <div class="lg:col-span-2 space-y-4">
                
                <!-- Shopping Cart -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-800 flex items-center">
                            <i class="bi bi-cart3 mr-2 text-purple-600"></i>
                            Keranjang Belanja
                        </h2>
                        <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ count(session('cart', [])) }} items
                        </span>
                    </div>

                    @if(session('cart') && count(session('cart')) > 0)
                        <div class="space-y-3 max-h-64 overflow-y-auto">
                            @foreach(session('cart') as $item)
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-100 hover:shadow-md transition-all">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($item['name'], 0, 2)) }}
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900 text-sm">{{ $item['name'] }}</h3>
                                            <p class="text-gray-500 text-xs">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <div class="flex items-center space-x-2">
                                            <input 
                                                type="number" 
                                                value="{{ $item['quantity'] }}" 
                                                min="1" 
                                                class="w-16 px-2 py-1 text-center border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                                onchange="updateQuantityGlobal({{ $item['id'] }}, this.value)"
                                            >
                                            <span class="text-sm text-gray-600">×</span>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-gray-900 text-sm">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>
                                        </div>
                                        <button 
                                            onclick="removeFromCartGlobal({{ $item['id'] }})"
                                            class="w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg flex items-center justify-center transition-colors"
                                        >
                                            <i class="bi bi-trash text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="bi bi-cart-x text-2xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Keranjang Kosong</h3>
                            <p class="text-gray-500 text-sm">Cari dan tambahkan produk untuk memulai transaksi</p>
                        </div>
                    @endif
                </div>

                <!-- Payment Section -->
                @if(session('cart') && count(session('cart')) > 0)
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <i class="bi bi-credit-card mr-2 text-green-600"></i>
                            Pembayaran
                        </h3>
                        
                        <!-- Total -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 mb-4">
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-700">Total Belanja:</span>
                                <span class="text-2xl font-bold text-green-600" id="grand-total">
                                    Rp {{ number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], session('cart', []))), 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Payment Input -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Uang Tunai</label>
                                <input 
                                    type="text" 
                                    id="cash-input" 
                                    placeholder="Masukkan jumlah uang"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-lg"
                                    oninput="handlePaymentInput(this)"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kembalian</label>
                                <div id="change-display" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-lg font-semibold text-gray-700">
                                    Rp 0
                                </div>
                            </div>
                        </div>

                        <!-- Quick Cash Buttons -->
                        <div class="grid grid-cols-4 gap-2 mb-4">
                            @foreach([50000, 100000, 200000, 500000] as $amount)
                                <button 
                                    onclick="setCashAmount({{ $amount }})"
                                    class="px-3 py-2 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-lg text-sm font-semibold transition-colors"
                                >
                                    {{ number_format($amount/1000, 0) }}k
                                </button>
                            @endforeach
                        </div>

                        <!-- Submit Button -->
                        <button 
                            onclick="submitTransactionGlobal()"
                            class="w-full bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white font-bold py-4 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg"
                        >
                            <i class="bi bi-check-circle mr-2"></i>
                            Selesaikan Transaksi
                        </button>
                    </div>
                @endif
            </div>

            <!-- Product Search Section (Right - 1 column) -->
            <div class="space-y-4">
                
                <!-- Search -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="bi bi-search mr-2 text-blue-600"></i>
                        Cari Produk
                    </h2>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="product-search" 
                            placeholder="Ketik nama atau kode produk..."
                            class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            oninput="doSearchGlobal(this.value)"
                        >
                        <i class="bi bi-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <!-- Search Results -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-white/20 overflow-hidden">
                    <div id="search-loading" class="hidden p-4 text-center">
                        <div class="inline-flex items-center space-x-2">
                            <div class="w-4 h-4 bg-blue-500 rounded-full animate-bounce"></div>
                            <div class="w-4 h-4 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                            <div class="w-4 h-4 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">Mencari produk...</p>
                    </div>
                    
                    <div id="search-results" class="max-h-96 overflow-y-auto">
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="bi bi-search text-2xl text-gray-400"></i>
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
// Get CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Global function untuk remove from cart
window.removeFromCartGlobal = async function(productId) {
    try {
        // Show confirmation with warning toast
        showWarning('Konfirmasi Hapus', 'Klik sekali lagi untuk menghapus produk dari keranjang', 0);
        
        // Use modern confirm with better UX
        if (!confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
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
            showSuccess('Produk Dihapus', data.message, 2000);
            
            // Reload page to refresh cart display
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showError('Gagal Menghapus', data.message || 'Gagal menghapus produk');
        }
        
    } catch (error) {
        console.error('Error removing from cart:', error);
        showError('Error Sistem', 'Terjadi kesalahan saat menghapus produk');
    }
};

// Function to handle payment input changes
window.handlePaymentInput = function(input) {
    // Clean input - only allow numbers and dots
    input.value = input.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
    
    // Update change calculation in real-time
    const grandTotalElement = document.getElementById('grand-total');
    if (grandTotalElement) {
        const grandTotalText = grandTotalElement.textContent.replace(/[^0-9]/g, '');
        const grandTotal = parseInt(grandTotalText) || 0;
        updateChangeCalculation(grandTotal);
    }
};

// Function to set quick cash amounts
window.setCashAmount = function(amount) {
    const cashInput = document.getElementById('cash-input');
    cashInput.value = new Intl.NumberFormat('id-ID').format(amount);
    
    // Trigger change calculation
    const grandTotalElement = document.getElementById('grand-total');
    if (grandTotalElement) {
        const grandTotalText = grandTotalElement.textContent.replace(/[^0-9]/g, '');
        const grandTotal = parseInt(grandTotalText) || 0;
        updateChangeCalculation(grandTotal);
    }
};

// Function to update change calculation
window.updateChangeCalculation = function(grandTotal) {
    const cashInput = document.getElementById('cash-input');
    const changeDisplay = document.getElementById('change-display');
    
    if (cashInput && changeDisplay) {
        // Parse cash input (remove thousand separators)
        const cashValue = parseInt(cashInput.value.replace(/[^0-9]/g, '')) || 0;
        const change = cashValue - grandTotal;
        
        if (change >= 0) {
            changeDisplay.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(change);
            changeDisplay.className = 'w-full px-4 py-3 bg-green-50 border border-green-300 rounded-xl text-lg font-semibold text-green-700';
        } else {
            changeDisplay.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.abs(change)) + ' kurang';
            changeDisplay.className = 'w-full px-4 py-3 bg-red-50 border border-red-300 rounded-xl text-lg font-semibold text-red-700';
        }
    }
};

// Function to update quantity
window.updateQuantityGlobal = async function(productId, newQuantity) {
    if (newQuantity < 1) {
        showWarning('Quantity Invalid', 'Quantity tidak boleh kurang dari 1');
        return;
    }
    
    try {
        const response = await fetch('/pos/update-quantity', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: parseInt(newQuantity)
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccess('Quantity Updated', 'Quantity berhasil diperbarui', 1500);
            setTimeout(() => {
                window.location.reload();
            }, 800);
        } else {
            showError('Update Failed', data.message || 'Gagal mengupdate quantity');
        }
    } catch (error) {
        console.error('Update quantity error:', error);
        showError('Error', 'Terjadi kesalahan saat mengupdate quantity');
    }
};

// Submit transaction function
window.submitTransactionGlobal = async function() {
    const cashInput = document.getElementById('cash-input');
    const grandTotalElement = document.getElementById('grand-total');
    
    if (!cashInput || !grandTotalElement) {
        showError('Error', 'Element tidak ditemukan');
        return;
    }
    
    const cashValue = parseInt(cashInput.value.replace(/[^0-9]/g, '')) || 0;
    const grandTotal = parseInt(grandTotalElement.textContent.replace(/[^0-9]/g, '')) || 0;
    
    if (cashValue < grandTotal) {
        showWarning('Uang Tidak Cukup', 'Jumlah uang tunai kurang dari total belanja');
        return;
    }
    
    if (grandTotal <= 0) {
        showWarning('Keranjang Kosong', 'Tambahkan produk ke keranjang terlebih dahulu');
        return;
    }
    
    try {
        const response = await fetch('/pos/submit-transaction', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({cash: cashValue})
        });
        const data = await response.json();
        
        if (data.success) {
            // Show success notification with toast
            showSuccess(
                'Transaksi Berhasil!', 
                `Kembalian: Rp ${new Intl.NumberFormat('id-ID').format(data.change)}. Total: Rp ${new Intl.NumberFormat('id-ID').format(data.total)}`,
                4000
            );
            
            // Reload after showing notification
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            showError('Transaksi Gagal', data.message || 'Gagal menyelesaikan transaksi');
        }
    } catch (error) {
        console.error('Transaction error:', error);
        showError('Error Sistem', 'Terjadi kesalahan saat menyelesaikan transaksi. Silakan coba lagi.');
    }
};

// Global function untuk search
window.doSearchGlobal = async function(term) {
    const resultsDiv = document.getElementById('search-results');
    const loadingDiv = document.getElementById('search-loading');
    
    if (!resultsDiv) {
        console.error('Search results div not found');
        return;
    }
    
    if (term.length < 2) {
        resultsDiv.innerHTML = `
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-search text-2xl text-gray-400"></i>
                </div>
                <p class="text-sm text-gray-500 font-medium">Mulai ketik untuk mencari produk</p>
                <p class="text-xs text-gray-400 mt-1">Minimal 2 karakter</p>
            </div>
        `;
        return;
    }
    
    // Show loading
    if (loadingDiv) {
        loadingDiv.classList.remove('hidden');
    }
    resultsDiv.innerHTML = '';
    
    try {
        const response = await fetch('/pos/search', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({search: term})
        });
        
        const data = await response.json();
        
        // Hide loading
        if (loadingDiv) {
            loadingDiv.classList.add('hidden');
        }
        
        if (data.success && data.products.length > 0) {
            let html = '';
            data.products.forEach(product => {
                html += `
                    <div class="p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer"
                         onclick="addToCartGlobal(${product.id})">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl flex items-center justify-center text-white font-bold">
                                    ${product.name.substring(0, 2).toUpperCase()}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 text-sm">${product.name}</h3>
                                    <p class="text-gray-500 text-xs">${product.category ? product.category.name : 'Tanpa Kategori'}</p>
                                    <p class="text-blue-600 font-bold text-sm">Rp ${new Intl.NumberFormat('id-ID').format(product.price)}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">
                                    Stok: ${product.stock}
                                </span>
                            </div>
                        </div>
                    </div>
                `;
            });
            resultsDiv.innerHTML = html;
        } else {
            resultsDiv.innerHTML = `
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-search-heart text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-sm text-gray-500 font-medium">Produk tidak ditemukan</p>
                    <p class="text-xs text-gray-400 mt-1">Coba kata kunci lain</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Search error:', error);
        if (loadingDiv) {
            loadingDiv.classList.add('hidden');
        }
        resultsDiv.innerHTML = `
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-exclamation-triangle text-2xl text-red-600"></i>
                </div>
                <p class="text-sm text-red-600 font-medium">Error saat mencari</p>
                <p class="text-xs text-gray-400 mt-1">Silakan coba lagi</p>
            </div>
        `;
    }
};

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
            showSuccess('Produk Ditambahkan', data.message, 2000);
            setTimeout(() => {
                window.location.reload(); // Refresh to update cart
            }, 1000);
        } else {
            showError('Gagal Menambahkan', data.message || 'Gagal menambahkan ke keranjang');
        }
    } catch (error) {
        console.error('Add to cart error:', error);
        showError('Error Sistem', 'Terjadi kesalahan saat menambahkan ke keranjang');
    }
};
</script>