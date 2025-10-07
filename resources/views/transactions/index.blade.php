<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">
                    Transaction History
                </h2>
                <p class="text-gray-300 text-sm mt-1">Riwayat dan laporan transaksi penjualan</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-4 bg-green-500/10 border-l-4 border-green-400 rounded-lg px-4 py-3 shadow-md">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-500/20 rounded-lg flex items-center justify-center">
                            <i class="bi bi-check-circle-fill text-green-400"></i>
                        </div>
                        <p class="text-white text-sm font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-500/10 border-l-4 border-red-400 rounded-lg px-4 py-3 shadow-md">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-red-500/20 rounded-lg flex items-center justify-center">
                            <i class="bi bi-exclamation-circle-fill text-red-400"></i>
                        </div>
                        <p class="text-white text-sm font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 overflow-hidden shadow-lg rounded-xl border border-gray-600/40">
                <div class="p-6" x-data="{ showCustomDate: {{ $filter == 'custom' ? 'true' : 'false' }} }">
                    <!-- Filter & Export Section -->
                    <div class="flex flex-col gap-4 mb-6">
                        <!-- Time Filter Buttons -->
                        <div class="flex flex-col sm:flex-row justify-between gap-4">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('transactions.index', ['filter' => 'all']) }}" 
                                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ (!request('filter') || request('filter') == 'all') ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg' : 'bg-gray-600/30 text-gray-300 hover:bg-gray-600/50' }}">
                                    Semua
                                </a>
                                <a href="{{ route('transactions.index', ['filter' => 'today']) }}" 
                                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request('filter') == 'today' ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg' : 'bg-gray-600/30 text-gray-300 hover:bg-gray-600/50' }}">
                                    Hari Ini
                                </a>
                                <a href="{{ route('transactions.index', ['filter' => 'yesterday']) }}" 
                                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request('filter') == 'yesterday' ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg' : 'bg-gray-600/30 text-gray-300 hover:bg-gray-600/50' }}">
                                    Kemarin
                                </a>
                                <a href="{{ route('transactions.index', ['filter' => 'this_week']) }}" 
                                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request('filter') == 'this_week' ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg' : 'bg-gray-600/30 text-gray-300 hover:bg-gray-600/50' }}">
                                    Minggu Ini
                                </a>
                                <a href="{{ route('transactions.index', ['filter' => 'last_week']) }}" 
                                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request('filter') == 'last_week' ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg' : 'bg-gray-600/30 text-gray-300 hover:bg-gray-600/50' }}">
                                    Minggu Lalu
                                </a>
                                <a href="{{ route('transactions.index', ['filter' => 'this_month']) }}" 
                                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request('filter') == 'this_month' ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg' : 'bg-gray-600/30 text-gray-300 hover:bg-gray-600/50' }}">
                                    Bulan Ini
                                </a>
                                <a href="{{ route('transactions.index', ['filter' => 'last_month']) }}" 
                                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request('filter') == 'last_month' ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg' : 'bg-gray-600/30 text-gray-300 hover:bg-gray-600/50' }}">
                                    Bulan Lalu
                                </a>
                                <a href="{{ route('transactions.index', ['filter' => 'this_year']) }}" 
                                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request('filter') == 'this_year' ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg' : 'bg-gray-600/30 text-gray-300 hover:bg-gray-600/50' }}">
                                    Tahun Ini
                                </a>
                                <button @click="showCustomDate = !showCustomDate" type="button"
                                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $filter == 'custom' ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg' : 'bg-gray-600/30 text-gray-300 hover:bg-gray-600/50' }}">
                                    <i class="bi bi-calendar-range mr-1"></i> Custom
                                </button>
                            </div>

                            <!-- Export Button -->
                            <a href="{{ route('transactions.export.excel') }}" 
                               class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-medium rounded-lg transition-all duration-200 shadow-md whitespace-nowrap">
                                <i class="bi bi-file-earmark-excel mr-2"></i> Export ke Excel
                            </a>
                        </div>

                        <!-- Custom Date Range Form (Hidden by default) -->
                        <div x-show="showCustomDate" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="bg-gradient-to-br from-gray-700/50 via-gray-800/50 to-gray-700/50 border border-gray-600/60 rounded-xl p-6 shadow-lg backdrop-blur-sm">
                            
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-lg">
                                    <i class="bi bi-calendar-range text-white text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-white font-bold text-base">Filter Custom</h3>
                                    <p class="text-gray-400 text-xs">Pilih rentang tanggal transaksi</p>
                                </div>
                            </div>

                            <form method="GET" action="{{ route('transactions.index') }}" class="space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Tanggal Mulai -->
                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-300">
                                            <i class="bi bi-calendar-check text-blue-400"></i>
                                            Tanggal Mulai
                                        </label>
                                        <div class="relative">
                                            <input type="date" 
                                                   name="start_date" 
                                                   value="{{ $startDate ?? '' }}"
                                                   class="w-full px-4 py-3 bg-white border-2 border-gray-600/50 rounded-lg text-gray-900 font-medium text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-500/70">
                                        </div>
                                    </div>

                                    <!-- Tanggal Akhir -->
                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-300">
                                            <i class="bi bi-calendar-x text-blue-400"></i>
                                            Tanggal Akhir
                                        </label>
                                        <div class="relative">
                                            <input type="date" 
                                                   name="end_date" 
                                                   value="{{ $endDate ?? '' }}"
                                                   class="w-full px-4 py-3 bg-white border-2 border-gray-600/50 rounded-lg text-gray-900 font-medium text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-500/70">
                                        </div>
                                    </div>
                                </div>

                                <!-- Buttons -->
                                <div class="flex flex-wrap gap-3 pt-2">
                                    <button type="submit" 
                                            class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105 transform">
                                        <i class="bi bi-funnel-fill"></i>
                                        Terapkan Filter
                                    </button>
                                    @if($filter == 'custom')
                                        <a href="{{ route('transactions.index') }}" 
                                           class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-600/40 hover:bg-gray-600/60 border border-gray-500/50 text-gray-300 hover:text-white font-semibold rounded-lg transition-all duration-200">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                            Reset Filter
                                        </a>
                                    @endif
                                </div>
                            </form>
                            
                            <!-- Active Filter Info -->
                            @if($filter == 'custom' && $startDate && $endDate)
                                <div class="mt-4 bg-gradient-to-r from-blue-500/10 to-blue-600/10 border-l-4 border-blue-400 rounded-lg px-4 py-3.5 shadow-md">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <i class="bi bi-info-circle-fill text-blue-300"></i>
                                        </div>
                                        <div>
                                            <p class="text-blue-200 text-sm font-bold mb-1">Filter Aktif</p>
                                            <p class="text-white text-sm font-medium">
                                                Menampilkan transaksi dari 
                                                <span class="font-bold text-blue-100 bg-blue-500/30 px-2 py-0.5 rounded">{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</span> 
                                                sampai 
                                                <span class="font-bold text-blue-100 bg-blue-500/30 px-2 py-0.5 rounded">{{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-600/40">
                        <table class="w-full">
                            <thead class="bg-gray-600/30">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">No Invoice</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Kasir</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Metode Bayar</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Total Harga</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-600/30">
                                @forelse ($transactions as $transaction)
                                    <tr class="hover:bg-gray-600/20 transition-colors duration-150">
                                        <td class="px-4 py-3 text-sm font-medium text-white">{{ $transaction->invoice_number }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-300">{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-300">{{ $transaction->user->name }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/20 text-white border border-blue-400/30">
                                                {{ $transaction->payment_method }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm font-semibold text-white">Rp {{ number_format($transaction->total_amount) }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <button type="button" 
                                                        @click="showModal('{{ $transaction->invoice_number }}', {{ json_encode($transaction->details) }}, '{{ $transaction->created_at->format('d M Y, H:i') }}', '{{ $transaction->payment_method }}', {{ $transaction->total_amount }})"
                                                        class="inline-flex items-center px-3 py-1.5 bg-blue-500/20 hover:bg-blue-500/30 text-white font-medium rounded-lg transition-all duration-200 border border-blue-400/30 text-sm">
                                                    <i class="bi bi-eye mr-1"></i> Lihat
                                                </button>
                                                <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi {{ $transaction->invoice_number }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center px-3 py-1.5 bg-red-500/20 hover:bg-red-500/30 text-white font-medium rounded-lg transition-all duration-200 border border-red-400/30 text-sm">
                                                        <i class="bi bi-trash mr-1"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <i class="bi bi-inbox text-gray-500 text-5xl mb-3"></i>
                                                <p class="text-gray-300 font-medium">Belum ada transaksi.</p>
                                                <p class="text-gray-400 text-sm mt-1">Transaksi yang dilakukan akan muncul di sini</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Detail Modal with Alpine.js -->
    <div x-data="{ 
        modalOpen: false, 
        invoice: '', 
        details: [], 
        date: '', 
        paymentMethod: '', 
        totalAmount: 0,
        init() {
            this.modalOpen = false;
            window.showModal = (invoiceNum, transactionDetails, transactionDate, payment, total) => {
                this.invoice = invoiceNum;
                this.details = transactionDetails;
                this.date = transactionDate;
                this.paymentMethod = payment;
                this.totalAmount = total;
                this.modalOpen = true;
            };
        }
    }" 
    @keydown.escape.window="modalOpen = false">
        
        <!-- Modal Overlay -->
        <div x-show="modalOpen" 
             x-cloak
             class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4"
             @click="modalOpen = false">            <!-- Modal Content -->
            <div @click.stop
                 class="bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 rounded-2xl shadow-2xl border border-gray-600/40 max-w-xl w-full max-h-[90vh] overflow-hidden">
                
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <h5 class="text-white font-bold text-2xl mb-1">Detail Transaksi</h5>
                            <p class="text-red-100 text-sm">Informasi lengkap transaksi</p>
                        </div>
                        <button type="button" @click="modalOpen = false" class="text-white hover:text-red-200 transition-colors p-2 hover:bg-white/10 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Modal Body -->
                <div class="bg-gray-800 text-white px-6 py-5 overflow-y-auto" style="max-height: calc(90vh - 200px);">
                    <!-- Transaction Info -->
                    <div class="bg-gray-700/50 rounded-xl p-4 mb-5 border border-gray-600/40">
                        <div class="space-y-3">
                            <div>
                                <p class="text-gray-400 text-xs uppercase mb-1">Invoice Number</p>
                                <p class="text-white font-bold text-base" x-text="invoice"></p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs uppercase mb-1">Tanggal & Waktu</p>
                                <p class="text-white font-semibold text-sm" x-text="date"></p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs uppercase mb-1">Metode Pembayaran</p>
                                <p class="text-white font-semibold">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-500/20 text-blue-300 border border-blue-400/30" x-text="paymentMethod"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Products Table -->
                    <div class="mb-4">
                        <h6 class="text-gray-300 font-semibold mb-3 text-sm uppercase tracking-wide">Detail Produk</h6>
                        <div class="overflow-x-auto rounded-lg border border-gray-600/40">
                            <table class="w-full">
                                <thead class="bg-gray-600/30">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Produk</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-white uppercase">Qty</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-white uppercase">Harga Satuan</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-white uppercase">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-600/30">
                                    <template x-for="item in details" :key="item.id">
                                        <tr class="hover:bg-gray-700/30 transition-colors">
                                            <td class="px-4 py-3 text-sm text-white font-medium">
                                                <span x-text="item.product ? item.product.name : 'Produk Dihapus'"></span>
                                                <template x-if="item.product && item.product.deleted_at">
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-500/20 text-red-300 border border-red-400/30">(Dihapus)</span>
                                                </template>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-white text-center">
                                                <span class="inline-block bg-gray-600/50 px-3 py-1 rounded-lg font-semibold" x-text="item.quantity"></span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-300 text-right" x-text="'Rp ' + Number(item.price).toLocaleString('id-ID')"></td>
                                            <td class="px-4 py-3 text-sm text-white font-semibold text-right" x-text="'Rp ' + Number(item.subtotal).toLocaleString('id-ID')"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Total Amount -->
                    <div class="bg-gradient-to-r from-emerald-600/20 to-emerald-700/20 border border-emerald-500/30 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-xs uppercase">Total Pembayaran</p>
                                    <p class="text-white font-bold text-2xl" x-text="'Rp ' + Number(totalAmount).toLocaleString('id-ID')"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="border-t border-gray-600/40 bg-gray-800 px-6 py-4 flex items-center justify-between">
                    <div class="text-gray-400 text-sm">
                        <i class="bi bi-info-circle mr-1"></i>
                        Detail transaksi lengkap
                    </div>
                    <button type="button" @click="modalOpen = false" class="px-6 py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-500 hover:to-gray-600 text-white font-semibold rounded-lg transition-all shadow-lg">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>