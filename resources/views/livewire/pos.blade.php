<div class="min-h-screen bg-gradient-to-br from-slate-800 via-slate-900 to-gray-900">
    <div class="container mx-auto px-4 py-4">
        <!-- Compact Header Section -->
        <div class="mb-4 bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 rounded-xl shadow-lg border border-gray-600/40 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">Point of Sale</h1>
                    <p class="text-sm text-gray-300">Kelola penjualan dan transaksi</p>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Toast Demo Buttons (for testing) -->
                    <div class="hidden" id="toast-demo">
                        <button onclick="showSuccess('Demo Success', 'This is a success message!')" class="px-3 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600">Success</button>
                        <button onclick="showError('Demo Error', 'This is an error message!')" class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Error</button>
                        <button onclick="showWarning('Demo Warning', 'This is a warning message!')" class="px-3 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">Warning</button>
                        <button onclick="showInfo('Demo Info', 'This is an info message!')" class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">Info</button>
                    </div>
                    
                    <div class="text-right">
                        <div class="text-sm text-gray-200 font-medium">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-400">{{ now()->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        @if (session()->has('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Compact Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
            <!-- Left Column - Cart & Payment (3 columns) -->
            <div class="lg:col-span-3">
                <!-- Shopping Cart -->
                <div class="bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 rounded-xl shadow-lg border border-gray-600/40 mb-4">
                    <div class="px-4 py-3 border-b border-gray-600/40">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-white flex items-center">
                                <i class="bi bi-cart3 mr-2 text-blue-400"></i>Keranjang Belanja
                            </h2>
                            <span class="bg-blue-500/20 text-white text-xs font-medium px-3 py-1 rounded-full border border-blue-400/30">
                                {{ count($cart) }} item{{ count($cart) != 1 ? 's' : '' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="overflow-hidden">
                        @if(count($cart) > 0)
                            <div class="max-h-80 overflow-y-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-600/30 sticky top-0">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase">Produk</th>
                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-300 uppercase w-20">Qty</th>
                                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-300 uppercase">Subtotal</th>
                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-300 uppercase w-12">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-600/30">
                                        @foreach ($cart as $item)
                                            <tr class="hover:bg-gray-600/20 transition-colors duration-150">
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10">
                                                            <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-md">
                                                                <i class="bi bi-box text-white"></i>
                                                            </div>
                                                        </div>
                                                        <div class="ml-3">
                                                            <div class="text-sm font-medium text-white">{{ $item['name'] }}</div>
                                                            <div class="text-xs text-gray-400">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <input type="number" 
                                                           onchange="updateQuantityGlobal({{ $item['id'] }}, this.value, {{ $item['price'] }})"
                                                           class="w-14 text-center bg-gray-600 border-gray-500 text-white rounded shadow-sm focus:border-blue-400 focus:ring-blue-400 text-sm" 
                                                           value="{{ $item['quantity'] }}" 
                                                           min="1"
                                                           step="1"
                                                           id="qty-{{ $item['id'] }}">
                                                </td>
                                                <td class="px-4 py-3 text-right">
                                                    <span class="text-sm font-semibold text-white" id="subtotal-{{ $item['id'] }}">
                                                        Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <button class="text-red-400 hover:text-red-300 transition-colors duration-150 p-1" 
                                                            onclick="removeFromCartGlobal({{ $item['id'] }})"
                                                            title="Hapus dari keranjang">
                                                        <i class="bi bi-trash text-sm"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="mx-auto h-16 w-16 text-gray-500 mb-3">
                                    <i class="bi bi-cart-x text-5xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-white mb-1">Keranjang Kosong</h3>
                                <p class="text-sm text-white">Cari dan tambahkan produk untuk memulai transaksi</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Section -->
                @if(count($cart) > 0)
                    <div class="bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 rounded-xl shadow-lg border border-gray-600/40">
                        <div class="px-4 py-3 border-b border-gray-600/40">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <i class="bi bi-credit-card mr-2 text-emerald-400"></i>Pembayaran
                            </h3>
                        </div>
                        
                        <div class="p-4 space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-1">Metode Pembayaran</label>
                                    <select wire:model.live="paymentMethod" 
                                            class="w-full bg-gray-600 border-gray-500 text-white rounded shadow-sm focus:border-blue-400 focus:ring-blue-400 text-sm">
                                        <option value="Cash">💵 Cash</option>
                                        <option value="Debit">💳 Debit Card</option>
                                        <option value="Credit Card">💎 Credit Card</option>
                                        <option value="Transfer">🏦 Bank Transfer</option>
                                        <option value="QRIS">📱 QRIS</option>
                                    </select>
                                </div>
                                
                                @if($paymentMethod === 'Cash')
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-1">Jumlah Dibayar</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                            <input type="number" 
                                                   id="amountPaidInput"
                                                   class="w-full pl-8 pr-4 py-2 bg-gray-600 border-gray-500 text-white rounded shadow-sm focus:border-blue-400 focus:ring-blue-400 text-sm @error('amountPaid') border-red-300 @enderror" 
                                                   placeholder="600000" 
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                @else
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-1">Jumlah Dibayar</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                            <input type="text" 
                                                   class="w-full pl-8 pr-4 py-2 bg-gray-700 border-gray-600 text-gray-300 rounded shadow-sm text-sm" 
                                                   value="{{ number_format($total, 0, ',', '.') }}" 
                                                   readonly>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1">Otomatis sesuai total untuk {{ $paymentMethod }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Total Summary -->
                            <div class="border-t border-gray-600/40 pt-4 mt-6">
                                <div class="flex justify-between items-center text-lg">
                                    <span class="font-semibold text-gray-300">Total Belanja:</span>
                                    <span class="font-bold text-2xl text-white" id="grand-total">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                                
                                @if($paymentMethod === 'Cash' && $amountPaid > 0)
                                    <div class="flex justify-between items-center text-md mt-2 pt-2 border-t border-gray-600/30">
                                        <span class="text-gray-300">Kembalian:</span>
                                        <span class="font-semibold text-emerald-400 text-lg" id="change-amount">
                                            Rp {{ number_format(max(0, floatval($amountPaid) - $total), 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Transaction Button -->
                            <div class="mt-6">
                                <button onclick="submitTransactionGlobal()" 
                                        type="button"
                                        class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-4 px-6 rounded-lg transition duration-300 transform hover:scale-105 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                                        {{ empty($cart) ? 'disabled' : '' }}>
                                    <i class="bi bi-check-circle-fill mr-2"></i>
                                    SELESAIKAN TRANSAKSI
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column - Product Search -->
                        <!-- Right Column - Product Search (2 columns) -->
            <div class="lg:col-span-2">
                <div class="bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 rounded-xl shadow-lg border border-gray-600/40">
                    <div class="px-4 py-3 border-b border-gray-600/40">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <i class="bi bi-search mr-2 text-purple-400"></i>Cari Produk
                        </h3>
                    </div>
                    
                    <div class="p-4">
                        <!-- Search Input -->
                        <div class="relative mb-4">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-search text-white"></i>
                            </div>
                            <input type="text" 
                                   onkeyup="doSearchGlobal(this.value)" 
                                   class="w-full pl-10 pr-4 py-2 bg-gray-600 border-gray-500 text-white placeholder-white font-medium rounded shadow-sm focus:ring-purple-400 focus:border-purple-400 text-sm"
                                   placeholder="Ketik nama atau kode produk..."
                                   autocomplete="off"
                                   id="search-input">
                        </div>

                        <!-- Search Results -->
                        <div class="max-h-80 overflow-y-auto">
                            <div id="search-results">
                                <div class="text-center py-8">
                                    <div class="mx-auto h-12 w-12 text-gray-500 mb-3">
                                        <i class="bi bi-search text-3xl"></i>
                                    </div>
                                    <p class="text-sm text-gray-300 font-medium">Mulai ketik untuk mencari produk</p>
                                    <p class="text-xs text-gray-400 mt-1">Minimal 2 karakter</p>
                                </div>
                            </div>
                            
                            <!-- Loading Indicator -->
                            <div id="search-loading" class="text-center py-6" style="display: none;">
                                <div class="inline-flex items-center text-blue-400">
                                    <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Mencari produk...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('POS System initialized successfully');
    
    // Auto-focus on search input
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.focus();
    }
});

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

// Global function untuk update quantity
window.updateQuantityGlobal = async function(productId, newQuantity, unitPrice) {
    try {
        // Validate quantity
        newQuantity = parseInt(newQuantity);
        if (newQuantity < 1) {
            newQuantity = 1;
            document.getElementById(`qty-${productId}`).value = 1;
        }
        
        // Update subtotal display immediately for better UX
        const subtotalElement = document.getElementById(`subtotal-${productId}`);
        if (subtotalElement) {
            const newSubtotal = unitPrice * newQuantity;
            subtotalElement.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(newSubtotal);
        }
        
        // Update cart total immediately
        updateCartTotals();
        
        // Send update to server
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
        
        if (!data.success) {
            // If server update fails, revert the display
            console.error('Failed to update quantity on server:', data.message);
            // You might want to reload the page or show an error message
        }
        
    } catch (error) {
        console.error('Error updating quantity:', error);
        // Optionally show error message to user
    }
};

// Function to recalculate cart totals
window.updateCartTotals = function() {
    let grandTotal = 0;
    
    // Loop through all quantity inputs to calculate new total
    document.querySelectorAll('[id^="qty-"]').forEach(qtyInput => {
        const productId = qtyInput.id.replace('qty-', '');
        const quantity = parseInt(qtyInput.value) || 0;
        
        // Find the unit price from the subtotal calculation
        const subtotalElement = document.getElementById(`subtotal-${productId}`);
        if (subtotalElement) {
            const subtotalText = subtotalElement.textContent.replace(/[^0-9]/g, '');
            const subtotal = parseInt(subtotalText) || 0;
            grandTotal += subtotal;
        }
    });
    
    // Update grand total display
    const grandTotalElement = document.getElementById('grand-total');
    if (grandTotalElement) {
        grandTotalElement.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal);
    }
    
    // Update change calculation if cash payment
    updateChangeCalculation(grandTotal);
};

// Function to update change calculation
window.updateChangeCalculation = function(grandTotal) {
    const cashInput = document.getElementById('amountPaidInput');
    const changeElement = document.getElementById('change-amount');
    
    if (cashInput && changeElement && cashInput.value) {
        const cashAmount = parseFloat(cashInput.value.replace(/[^0-9]/g, '')) || 0;
        const change = Math.max(0, cashAmount - grandTotal);
        changeElement.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(change);
    }
};
window.submitTransactionGlobal = async function() {
    try {
        // Get cash amount from input field directly
        const cashInput = document.getElementById('amountPaidInput');
        let cashAmount = 0;
        
        if (cashInput && cashInput.value) {
            // Remove formatting and convert to number
            cashAmount = parseFloat(cashInput.value.replace(/[^0-9]/g, ''));
        }
        
        console.log('Processing transaction with cash amount:', cashAmount);
        console.log('Input value:', cashInput ? cashInput.value : 'not found');
        
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
                <div class="mx-auto h-16 w-16 text-gray-500 mb-4">
                    <i class="bi bi-search text-4xl"></i>
                </div>
                <p class="text-sm text-gray-300 font-medium">Mulai ketik untuk mencari produk</p>
                <p class="text-xs text-gray-400 mt-1">Minimal 2 karakter</p>
            </div>
        `;
        if (loadingDiv) loadingDiv.style.display = 'none';
        return;
    }
    
    // Show loading
    if (loadingDiv) loadingDiv.style.display = 'block';
    resultsDiv.innerHTML = '';
    
    try {
        console.log('Searching for:', term);
        const response = await fetch('/pos/search', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
            },
            body: JSON.stringify({search: term})
        });
        
        console.log('Response status:', response.status);
        const data = await response.json();
        console.log('Search response:', data);
        
        // Hide loading
        if (loadingDiv) loadingDiv.style.display = 'none';
        
        if (data.products && data.products.length > 0) {
            let html = `
                <div class="mb-3 px-3 py-2 bg-blue-500/20 rounded-lg border border-blue-400/30">
                    <p class="text-xs font-medium text-white">
                        <i class="bi bi-info-circle mr-1"></i>
                        Ditemukan ${data.products.length} produk untuk "${term}"
                    </p>
                </div>
                <div class="space-y-2">
            `;
            
            data.products.forEach(product => {
                const stockColor = product.stock > 5 ? 'text-emerald-400' : 'text-amber-400';
                const stockIcon = product.stock > 5 ? 'bi-check-circle' : 'bi-exclamation-triangle';
                
                html += `
                    <div class="p-3 border border-gray-600/40 rounded-lg hover:border-blue-400/50 hover:bg-gray-600/20 cursor-pointer transition-all duration-200 group" 
                         onclick="addToCartGlobal(${product.id})"
                         title="Klik untuk menambah ke keranjang">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-white group-hover:text-blue-300 transition-colors duration-200 truncate text-sm">
                                    ${product.name}
                                </h4>
                                <div class="flex items-center mt-1 space-x-2">
                                    <span class="text-xs text-white bg-gray-600/40 px-1.5 py-0.5 rounded font-medium">
                                        ${product.code}
                                    </span>
                                    <span class="text-xs text-white flex items-center font-medium">
                                        <i class="bi ${stockIcon} mr-1 ${stockColor}"></i>
                                        ${product.stock}
                                    </span>
                                    ${product.category?.name ? `
                                        <span class="text-xs text-white bg-purple-500/20 px-1.5 py-0.5 rounded border border-purple-400/30">
                                            ${product.category.name}
                                        </span>
                                    ` : ''}
                                </div>
                            </div>
                            <div class="text-right ml-2">
                                <p class="font-bold text-white">
                                    Rp ${new Intl.NumberFormat('id-ID').format(product.sell_price)}
                                </p>
                                <div class="mt-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-500/20 text-white border border-blue-400/30">
                                        <i class="bi bi-plus-circle mr-1"></i>
                                        Tambah
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            resultsDiv.innerHTML = html;
        } else {
            resultsDiv.innerHTML = `
                <div class="text-center py-12">
                    <div class="mx-auto h-16 w-16 text-gray-500 mb-4">
                        <i class="bi bi-search-heart text-4xl"></i>
                    </div>
                    <h3 class="text-sm font-medium text-gray-300 mb-2">Produk tidak ditemukan</h3>
                    <p class="text-xs text-gray-400">Coba gunakan kata kunci lain atau periksa ejaan</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Search error:', error);
        if (loadingDiv) loadingDiv.style.display = 'none';
        resultsDiv.innerHTML = `
            <div class="text-center py-12">
                <div class="mx-auto h-16 w-16 text-red-400 mb-4">
                    <i class="bi bi-exclamation-triangle text-4xl"></i>
                </div>
                <h3 class="text-sm font-medium text-red-300 mb-2">Terjadi kesalahan</h3>
                <p class="text-xs text-red-400">${error.message}</p>
            </div>
        `;
    }
};

// Global function untuk add to cart
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