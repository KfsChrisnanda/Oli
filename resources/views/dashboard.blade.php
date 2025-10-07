<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">Dashboard</h1>
                <p class="text-gray-200 mt-1">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        
        <!-- Period Filter and Search Section -->
        <div class="bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 rounded-xl shadow-lg border border-gray-600/40 p-6">
            <form method="GET" action="{{ route('dashboard') }}" class="flex items-end gap-4">
                <div class="flex-1">
                    <label for="period" class="block text-sm font-medium text-gray-200 mb-2">Filter Periode</label>
                    <select name="period" id="period" class="w-full px-4 py-3 border-2 border-gray-500 bg-gray-600 text-white rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" onchange="toggleCustomDate()">
                        <option value="today" class="bg-gray-700 text-white" {{ request('period', 'today') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="this_week" class="bg-gray-700 text-white" {{ request('period') == 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="this_month" class="bg-gray-700 text-white" {{ request('period') == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="last_month" class="bg-gray-700 text-white" {{ request('period') == 'last_month' ? 'selected' : '' }}>Bulan Lalu</option>
                        <option value="this_year" class="bg-gray-700 text-white" {{ request('period') == 'this_year' ? 'selected' : '' }}>Tahun Ini</option>
                        <option value="custom" class="bg-gray-700 text-white" {{ request('period') == 'custom' ? 'selected' : '' }}>Periode Kustom</option>
                    </select>
                </div>
                
                <div id="customDateRange" class="flex gap-3" style="{{ request('period') == 'custom' ? '' : 'display: none;' }}">
                    <div>
                        <label class="block text-sm font-medium text-gray-200 mb-2">Dari</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-4 py-3 border-2 border-gray-500 bg-gray-600 text-white rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-200 mb-2">Sampai</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-4 py-3 border-2 border-gray-500 bg-gray-600 text-white rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all font-medium shadow-lg">
                    <i class="bi bi-funnel mr-2"></i>Filter
                </button>
                
                @if(request()->hasAny(['period', 'start_date', 'end_date']) && request('period') != 'today')
                    <a href="{{ route('dashboard') }}" class="px-4 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-gray-200 rounded-lg hover:from-gray-500 hover:to-gray-600 transition-all shadow-md">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                @endif
            </form>
        </div>

        <!-- Stats Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <!-- Sales Card -->
            <div class="relative group overflow-hidden rounded-2xl bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 border border-gray-600/40 p-6 shadow-2xl transition-all duration-500 hover:scale-105">
                <div class="relative flex items-center justify-between">
                    <div>
                        <p class="text-white text-sm font-medium mb-2">Total Penjualan</p>
                        <p class="text-white text-2xl font-bold">Rp {{ number_format($stats['salesToday'], 0, ',', '.') }}</p>
                        <div class="mt-2 flex items-center text-xs text-white font-medium">
                            <i class="bi bi-arrow-up-right mr-1 text-white"></i>
                            <span>+12.5% dari kemarin</span>
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="bi bi-currency-dollar text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Profit Card -->
            <div class="relative group overflow-hidden rounded-2xl bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 border border-gray-600/40 p-6 shadow-2xl transition-all duration-500 hover:scale-105">
                <div class="relative flex items-center justify-between">
                    <div>
                        <p class="text-white text-sm font-medium mb-2">Keuntungan</p>
                        <p class="text-white text-2xl font-bold">Rp {{ number_format($stats['profitToday'], 0, ',', '.') }}</p>
                        <div class="mt-2 flex items-center text-xs text-white font-medium">
                            <i class="bi bi-arrow-up-right mr-1 text-white"></i>
                            <span>+8.2% margin</span>
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="bi bi-graph-up text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Transaction Card -->
            <div class="relative group overflow-hidden rounded-2xl bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 border border-gray-600/40 p-6 shadow-2xl transition-all duration-500 hover:scale-105">
                <div class="relative flex items-center justify-between">
                    <div>
                        <p class="text-white text-sm font-medium mb-2">Transaksi</p>
                        <p class="text-white text-2xl font-bold">{{ $stats['transactionsTodayCount'] }}</p>
                        <div class="mt-2 flex items-center text-xs text-white">
                            <i class="bi bi-clock mr-1 text-white"></i>
                            <span>Hari ini</span>
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-gray-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="bi bi-receipt text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Low Stock Card -->
            <div class="relative group overflow-hidden rounded-2xl bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 border border-gray-600/40 p-6 shadow-2xl transition-all duration-500 hover:scale-105">
                <div class="relative flex items-center justify-between">
                    <div>
                        <p class="text-white text-sm font-medium mb-2">Stok Menipis</p>
                        <p class="text-white text-2xl font-bold">{{ $stats['lowStockProductsCount'] }}</p>
                        <div class="mt-2 flex items-center text-xs {{ $stats['lowStockProductsCount'] > 0 ? 'text-white font-medium' : 'text-white font-medium' }}">
                            @if($stats['lowStockProductsCount'] > 0)
                                <i class="bi bi-exclamation-triangle mr-1 text-white"></i>
                                <span>Perlu perhatian</span>
                            @else
                                <i class="bi bi-check2-circle mr-1 text-white"></i>
                                <span>Stok aman</span>
                            @endif
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="bi bi-exclamation-triangle text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart and Lists Section -->
        <div class="grid lg:grid-cols-3 gap-8">
            
            <!-- Sales Chart - Takes 2/3 width -->
            <div class="lg:col-span-2 bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 rounded-2xl shadow-lg p-8 border border-gray-600/40">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-bold text-white">Grafik Penjualan 7 Hari Terakhir</h3>
                        <p class="text-white text-sm mt-1">Analisis performa penjualan harian</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></div>
                        <span class="text-white text-sm font-medium">Live Analytics</span>
                    </div>
                </div>
                <div class="h-80 relative">
                    <canvas id="salesChart" class="w-full h-full"></canvas>
                </div>
            </div>
            
            <!-- Right Panel - Lists -->
            <div class="space-y-8">
                
                <!-- Top Products -->
                <div class="bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 rounded-2xl p-6 border border-gray-600/40 shadow-lg">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="font-bold text-white">Produk Terlaris</h3>
                            <p class="text-white text-sm">Top performers hari ini</p>
                        </div>
                        <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="bi bi-trophy text-white text-lg"></i>
                        </div>
                    </div>
                    <div class="space-y-4">
                        @forelse($stats['topSellingProducts'] as $index => $item)
                            <div class="flex items-center justify-between p-4 bg-gray-600/30 rounded-xl hover:bg-gray-600/40 transition-all duration-300 border border-gray-500/30">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 bg-gradient-to-br from-gray-500 to-gray-600 text-white rounded-xl flex items-center justify-center text-sm font-bold shadow-lg">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-white text-sm">
                                            {{ $item->product ? Str::limit($item->product->name, 20) : 'Produk Dihapus' }}
                                            @if($item->product && $item->product->trashed())
                                                <span class="text-xs text-red-400">(Dihapus)</span>
                                            @endif
                                        </p>
                                        <p class="text-white text-xs">{{ $item->total_quantity }} terjual</p>
                                    </div>
                                </div>
                                <div class="w-2 h-8 bg-gradient-to-t from-blue-500 to-blue-400 rounded-full"></div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-gray-600/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <i class="bi bi-inbox text-white text-2xl"></i>
                                </div>
                                <p class="text-white text-sm">Belum ada data penjualan</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Low Stock Alert -->
                @if($stats['lowStockProductsCount'] > 0)
                <div class="bg-gradient-to-br from-red-700 via-red-800 to-red-900 rounded-2xl shadow-lg border border-red-600/40 p-6 relative overflow-hidden">
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-red-500/30 rounded-full flex items-center justify-center mr-3">
                                    <i class="bi bi-exclamation-triangle text-white text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-white">🚨 Alert: Stok Menipis!</h3>
                                    <p class="text-red-200 text-xs">Perlu tindakan segera</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="bg-red-500/30 text-white text-xs font-bold px-3 py-1 rounded-full">
                                    {{ $stats['lowStockProductsCount'] }} Produk
                                </span>
                            </div>
                        </div>
                        
                        <div class="space-y-3 max-h-60 overflow-y-auto">
                            @foreach($stats['lowStockProductsList'] as $product)
                                <div class="flex items-center justify-between p-3 bg-red-600/20 rounded-lg shadow-sm hover:bg-red-600/30 transition-all border border-red-500/30">
                                    <div class="flex items-center space-x-3 flex-1">
                                        <div class="w-10 h-10 bg-red-500/30 rounded-lg flex items-center justify-center">
                                            <i class="bi bi-box text-white"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium text-white text-sm">{{ $product->name }}</p>
                                            <div class="flex items-center space-x-2 mt-1">
                                                <span class="text-red-200 text-xs font-bold">
                                                    Sisa: {{ $product->stock }} unit
                                                </span>
                                                @if($product->stock <= 5)
                                                    <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">
                                                        KRITIS
                                                    </span>
                                                @elseif($product->stock <= 10)
                                                    <span class="bg-orange-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">
                                                        RENDAH
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right ml-3">
                                        <button onclick="showRestockModal('{{ $product->id }}', '{{ $product->name }}')" 
                                                class="bg-red-500/30 hover:bg-red-500/50 text-white text-xs px-3 py-1.5 rounded-lg font-medium transition-colors border border-red-400/30">
                                            <i class="bi bi-plus-lg mr-1"></i>Restock
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="mt-4 flex justify-between items-center pt-4 border-t border-red-600/30">
                            <p class="text-red-100 text-sm">
                                <i class="bi bi-info-circle mr-1"></i>
                                Segera lakukan restock untuk menghindari kehabisan stok
                            </p>
                            <a href="{{ route('products.index', ['filter' => 'low_stock']) }}" 
                               class="bg-red-500/30 hover:bg-red-500/50 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors border border-red-400/30">
                                Kelola Semua
                            </a>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-gradient-to-br from-green-800 to-green-900 rounded-xl shadow-lg border border-green-700 p-6 relative overflow-hidden">
                    <div class="relative text-center py-4">
                        <div class="w-16 h-16 bg-green-700/50 rounded-full flex items-center justify-center mx-auto mb-3 border border-green-600">
                            <i class="bi bi-check-circle text-green-200 text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-green-100 mb-1">✅ Stok Aman</h3>
                        <p class="text-green-300 text-sm">Semua produk memiliki stok yang cukup</p>
                        <a href="{{ route('products.index') }}" class="inline-flex items-center text-green-300 hover:text-green-100 text-sm font-medium mt-3">
                            <i class="bi bi-eye mr-1"></i>
                            Lihat Semua Produk
                        </a>
                    </div>
                </div>
                @endif

            </div>
        </div>

    </div>

    <!-- Restock Modal -->
    <div id="restockModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 rounded-2xl shadow-2xl border border-gray-600/40 max-w-md w-full overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-red-700 to-red-900 px-6 py-5 flex items-center justify-between" style="background-image: linear-gradient(90deg, #dc2626 0%, #b91c1c 45%, #7f1d1d 100%);">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Restock Produk</h3>
                        <p class="text-red-100 text-sm">Tambah stok inventory</p>
                    </div>
                </div>
                <button onclick="closeRestockModal()" class="text-white/80 hover:text-white hover:bg-white/10 rounded-lg p-2 transition-colors flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-8 space-y-6">
                <!-- Product Name -->
                <div>
                    <label class="block text-gray-300 text-sm font-semibold mb-3">Nama Produk</label>
                    <div class="bg-gray-600/40 border border-gray-500/40 rounded-xl px-5 py-4">
                        <p id="modalProductName" class="text-white font-semibold text-lg"></p>
                    </div>
                </div>

                <!-- Quantity Input -->
                <div>
                    <label for="restockQuantity" class="block text-white text-sm font-semibold mb-3">
                        Jumlah Stok Tambahan
                    </label>
                    <div class="relative">
                        <input 
                            type="number" 
                            id="restockQuantity" 
                            min="1" 
                            value="20"
                            class="w-full bg-gray-700 border-2 border-gray-600 text-white text-2xl font-bold rounded-xl px-6 py-4 focus:outline-none focus:ring-2 focus:ring-white focus:border-white transition-all placeholder-gray-500"
                            placeholder="0"
                        >
                    </div>
                    <div class="flex items-center gap-2 mt-3 text-gray-300 text-xs">
                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Stok akan ditambahkan ke stok yang sudah ada</span>
                    </div>
                </div>

                <!-- Quick Select -->
                <div>
                    <label class="block text-white text-xs font-semibold uppercase tracking-wider mb-3">Quick Select</label>
                    <div class="grid grid-cols-4 gap-3">
                        <button onclick="setRestockQuantity(10)" class="group bg-gray-700 hover:bg-gradient-to-r hover:from-red-700 hover:to-red-800 border-2 border-gray-600 hover:border-red-600 rounded-lg py-3.5 transition-all duration-200">
                            <div class="text-center">
                                <div class="text-white group-hover:text-red-200 text-xs font-semibold mb-1">+</div>
                                <div class="text-white text-2xl font-bold">10</div>
                            </div>
                        </button>
                        <button onclick="setRestockQuantity(20)" class="group bg-gray-700 hover:bg-gradient-to-r hover:from-red-700 hover:to-red-800 border-2 border-gray-600 hover:border-red-600 rounded-lg py-3.5 transition-all duration-200">
                            <div class="text-center">
                                <div class="text-white group-hover:text-red-200 text-xs font-semibold mb-1">+</div>
                                <div class="text-white text-2xl font-bold">20</div>
                            </div>
                        </button>
                        <button onclick="setRestockQuantity(50)" class="group bg-gray-700 hover:bg-gradient-to-r hover:from-red-700 hover:to-red-800 border-2 border-gray-600 hover:border-red-600 rounded-lg py-3.5 transition-all duration-200">
                            <div class="text-center">
                                <div class="text-white group-hover:text-red-200 text-xs font-semibold mb-1">+</div>
                                <div class="text-white text-2xl font-bold">50</div>
                            </div>
                        </button>
                        <button onclick="setRestockQuantity(100)" class="group bg-gray-700 hover:bg-gradient-to-r hover:from-red-700 hover:to-red-800 border-2 border-gray-600 hover:border-red-600 rounded-lg py-3.5 transition-all duration-200">
                            <div class="text-center">
                                <div class="text-white group-hover:text-red-200 text-xs font-semibold mb-1">+</div>
                                <div class="text-white text-2xl font-bold">100</div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-6 bg-gray-800/50 backdrop-blur-sm border-t border-gray-600/40">
                <div class="flex gap-4">
                    <button 
                        onclick="closeRestockModal()" 
                        class="flex-1 bg-gray-700 hover:bg-gray-600 text-white py-2.5 px-4 rounded-xl font-semibold transition-all duration-200 border border-gray-600 hover:border-gray-500 flex items-center justify-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span>Batal</span>
                    </button>
                    <button 
                        id="submitRestockBtn"
                        onclick="submitRestock()" 
                        class="flex-1 bg-gradient-to-r from-red-700 to-red-900 hover:from-red-800 hover:to-red-900 text-white py-2.5 px-4 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2 border border-red-700/60 focus:outline-none focus:ring-2 focus:ring-red-800 focus:ring-offset-2 focus:ring-offset-gray-800 disabled:cursor-not-allowed disabled:opacity-90 disabled:bg-gradient-to-r disabled:from-red-900 disabled:to-red-900"
                        style="background-image: linear-gradient(90deg, #991b1b 0%, #7f1d1d 100%); border-color: rgba(185, 28, 28, 0.6); white-space: nowrap;"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Konfirmasi Restock</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
        <style>
            /* Custom Scrollbar */
            ::-webkit-scrollbar {
                width: 6px;
            }
            
            ::-webkit-scrollbar-track {
                background: rgba(71, 85, 105, 0.5);
                border-radius: 6px;
            }
            
            ::-webkit-scrollbar-thumb {
                background: rgba(148, 163, 184, 0.8);
                border-radius: 6px;
                border: 1px solid rgba(100, 116, 139, 0.5);
            }
            
            ::-webkit-scrollbar-thumb:hover {
                background: rgba(203, 213, 225, 0.9);
            }

            /* Modal Animation */
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            .animate-slide-in {
                animation: slideIn 0.3s ease-out;
            }

            /* Spinning animation for loading */
            @keyframes spin {
                from {
                    transform: rotate(0deg);
                }
                to {
                    transform: rotate(360deg);
                }
            }

            .animate-spin {
                animation: spin 1s linear infinite;
            }
        </style>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            function toggleCustomDate() {
                const periodSelect = document.getElementById('period');
                const customDateRange = document.getElementById('customDateRange');
                
                if (periodSelect.value === 'custom') {
                    customDateRange.style.display = 'block';
                } else {
                    customDateRange.style.display = 'none';
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('salesChart');
                if (ctx) {
                    const salesData = {!! $stats['salesChartData'] !!};

                    const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
                    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.6)');
                    gradient.addColorStop(0.5, 'rgba(79, 70, 229, 0.4)');
                    gradient.addColorStop(1, 'rgba(67, 56, 202, 0.2)');

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: salesData.labels,
                            datasets: [{
                                label: 'Penjualan (Rp)',
                                data: salesData.values,
                                backgroundColor: gradient,
                                borderColor: '#3b82f6',
                                borderWidth: 2,
                                borderRadius: 8,
                                borderSkipped: false,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: {
                                duration: 2000,
                                easing: 'easeInOutQuart'
                            },
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(15, 23, 42, 0.8)',
                                    titleColor: '#e2e8f0',
                                    bodyColor: '#e2e8f0',
                                    borderColor: '#334155',
                                    borderWidth: 1,
                                    cornerRadius: 8,
                                    displayColors: false,
                                    titleFont: {
                                        size: 14,
                                        weight: 'bold'
                                    },
                                    bodyFont: {
                                        size: 13
                                    },
                                    callbacks: {
                                        label: function(context) {
                                            return 'Penjualan: Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(51, 65, 85, 0.5)',
                                        drawBorder: false
                                    },
                                    ticks: {
                                        color: '#94a3b8',
                                        font: {
                                            size: 12,
                                            weight: '500'
                                        },
                                        callback: function(value) {
                                            return 'Rp ' + new Intl.NumberFormat('id-ID', {
                                                notation: 'compact',
                                                maximumFractionDigits: 1
                                            }).format(value);
                                        }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: '#94a3b8',
                                        font: {
                                            size: 12,
                                            weight: '500'
                                        }
                                    }
                                }
                            },
                            onHover: function(event, activeElements) {
                                event.native.target.style.cursor = activeElements.length > 0 ? 'pointer' : 'default';
                            }
                        }
                    });
                }

                window.showRestockModal = function(productId, productName) {
                    // Set data ke modal
                    document.getElementById('modalProductName').textContent = productName;
                    document.getElementById('restockQuantity').value = 20;
                    
                    // Simpan productId untuk digunakan saat submit
                    window.currentRestockProductId = productId;
                    window.currentRestockProductName = productName;
                    
                    // Tampilkan modal
                    const modal = document.getElementById('restockModal');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    
                    // Focus ke input
                    setTimeout(() => {
                        document.getElementById('restockQuantity').focus();
                        document.getElementById('restockQuantity').select();
                    }, 100);
                };

                window.closeRestockModal = function() {
                    const modal = document.getElementById('restockModal');
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                };

                window.setRestockQuantity = function(quantity) {
                    document.getElementById('restockQuantity').value = quantity;
                };

                window.submitRestock = function() {
                    const quantity = parseInt(document.getElementById('restockQuantity').value);
                    
                    if (!quantity || quantity <= 0) {
                        alert('Mohon masukkan jumlah stok yang valid');
                        return;
                    }

                    const productId = window.currentRestockProductId;
                    const productName = window.currentRestockProductName;

                    // Disable button untuk mencegah double submit
                    const submitBtn = document.getElementById('submitRestockBtn');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<svg class="animate-spin w-5 h-5 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';

                    // Kirim request ke backend
                    fetch(`/products/${productId}/restock`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            stock: quantity
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Tutup modal
                            closeRestockModal();
                            
                            // Tampilkan notifikasi sukses
                            showSuccessNotification(data.message);
                            
                            // Reload halaman setelah 1.5 detik
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            alert('Gagal menambah stok. Silakan coba lagi.');
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Konfirmasi Restock';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Konfirmasi Restock';
                    });
                };

                window.showSuccessNotification = function(message) {
                    // Buat elemen notifikasi
                    const notification = document.createElement('div');
                    notification.className = 'fixed top-4 right-4 z-50 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-6 py-4 rounded-lg shadow-2xl border border-emerald-500 flex items-center gap-3 animate-slide-in';
                    notification.innerHTML = `
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold">Berhasil!</p>
                            <p class="text-sm text-emerald-100">${message}</p>
                        </div>
                    `;
                    
                    document.body.appendChild(notification);
                    
                    // Hapus setelah 3 detik
                    setTimeout(() => {
                        notification.style.opacity = '0';
                        notification.style.transform = 'translateX(100%)';
                        setTimeout(() => notification.remove(), 300);
                    }, 3000);
                };

                // Close modal when clicking outside
                document.getElementById('restockModal')?.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeRestockModal();
                    }
                });

                // Handle Enter key in input
                document.getElementById('restockQuantity')?.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        submitRestock();
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>