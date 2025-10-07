<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-6">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Kelola Produk
            </h2>
            <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Produk
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-emerald-500/20 border border-emerald-400/30 text-white px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-500/20 border border-red-400/30 text-white px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Search and Filter -->
            <div class="bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 backdrop-blur-sm rounded-xl shadow-xl border border-gray-600/40 p-6 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="relative flex-1">
                        <input type="text" id="searchInput" placeholder="Cari produk..." class="w-full bg-gray-600 border border-gray-500 text-white placeholder-gray-400 rounded-lg px-4 py-2.5 pl-10 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <div class="flex items-center gap-4">
                        <select class="bg-gray-600 border border-gray-500 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" id="categoryFilter">
                            <option value="">Semua Kategori</option>
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <select class="bg-gray-600 border border-gray-500 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" id="stockFilter">
                            <option value="">Semua Stok</option>
                            <option value="low">Stok Menipis</option>
                            <option value="available">Stok Tersedia</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">
                    @foreach($products as $product)
                        <div class="bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 rounded-xl shadow-lg border border-gray-600/40 overflow-hidden hover:shadow-2xl hover:scale-105 transition-all duration-300 group">
                            <!-- Product Image -->
                            <div class="relative aspect-square overflow-hidden bg-gray-600/20">
                                @if($product->image && file_exists(public_path('storage/' . $product->image)))
                                    <img src="{{ Storage::url($product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                         onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                @endif
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-600 to-gray-700" style="{{ $product->image && file_exists(public_path('storage/' . $product->image)) ? 'display: none;' : '' }}">
                                    <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path>
                                    </svg>
                                </div>
                                
                                <!-- Stock Status Badge -->
                                @if($product->stock <= $product->minimum_stock)
                                    <div class="absolute top-2 right-2">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-500 text-white shadow-lg">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            Stok Menipis
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="p-4">
                                <!-- Product Name -->
                                <h3 class="font-bold text-white text-lg mb-2 line-clamp-2 min-h-[3.5rem]">
                                    {{ $product->name }}
                                </h3>

                                <!-- Category Badge -->
                                <div class="mb-3">
                                    <span class="inline-block text-xs font-medium px-2.5 py-1 rounded-lg bg-purple-500/20 text-purple-200 border border-purple-400/30">
                                        {{ $product->category->name ?? 'Tanpa Kategori' }}
                                    </span>
                                </div>

                                <!-- Product Details -->
                                <div class="space-y-2 mb-4">
                                    <!-- Stock -->
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-300">Stok:</span>
                                        <span class="font-semibold text-white">
                                            {{ $product->stock }} / {{ $product->minimum_stock }}
                                        </span>
                                    </div>

                                    <!-- Price -->
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-300 text-sm">Harga:</span>
                                        <span class="font-bold text-emerald-400 text-lg">
                                            Rp {{ number_format($product->sell_price, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <!-- Code -->
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-gray-400">Kode:</span>
                                        <span class="font-mono text-gray-300 bg-gray-600/50 px-2 py-0.5 rounded">
                                            {{ $product->code }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-2 pt-3 border-t border-gray-600/40">
                                    <a href="{{ route('products.edit', $product) }}" 
                                       class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-all text-sm font-medium shadow-md hover:shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('products.destroy', $product) }}" class="flex-1" id="deleteForm{{ $product->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                onclick="deleteProduct{{ $product->id }}()"
                                                class="w-full inline-flex items-center justify-center gap-1 px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all text-sm font-medium shadow-md hover:shadow-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                    <script>
                                    function deleteProduct{{ $product->id }}() {
                                        if (confirm('Apakah Anda yakin ingin menghapus produk ini?\n\nProduk: {{ str_replace("'", "\\'", $product->name) }}\n\nProduk yang dihapus tidak dapat dikembalikan!')) {
                                            document.getElementById('deleteForm{{ $product->id }}').submit();
                                        }
                                    }
                                    </script>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 rounded-xl shadow-lg border border-gray-600/40 px-6 py-4">
                        {{ $products->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 rounded-xl shadow-lg border border-gray-600/40 py-16">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="w-24 h-24 text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path>
                        </svg>
                        <p class="text-gray-300 text-xl font-medium mb-2">Belum ada produk</p>
                        <p class="text-gray-400 mb-6">Mulai tambahkan produk untuk mengelola inventory</p>
                        <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Produk Pertama
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        // Simple search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const cards = document.querySelectorAll('.grid > div');
                
                cards.forEach(card => {
                    const text = card.textContent.toLowerCase();
                    card.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        }

        // Filter functionality
        document.getElementById('categoryFilter').addEventListener('change', function() {
            filterProducts();
        });

        document.getElementById('stockFilter').addEventListener('change', function() {
            filterProducts();
        });

        function filterProducts() {
            const categoryFilter = document.getElementById('categoryFilter').value.toLowerCase();
            const stockFilter = document.getElementById('stockFilter').value;
            const cards = document.querySelectorAll('.grid > div');
            
            cards.forEach(card => {
                let showCard = true;
                const cardText = card.textContent.toLowerCase();
                
                // Category filter
                if (categoryFilter) {
                    if (!cardText.includes(categoryFilter)) {
                        showCard = false;
                    }
                }
                
                // Stock filter
                if (stockFilter && showCard) {
                    const hasLowStockBadge = card.querySelector('.bg-red-500');
                    if (stockFilter === 'low' && !hasLowStockBadge) {
                        showCard = false;
                    } else if (stockFilter === 'available' && hasLowStockBadge) {
                        showCard = false;
                    }
                }
                
                card.style.display = showCard ? '' : 'none';
            });
        }
    </script>
    @endpush
</x-app-layout>