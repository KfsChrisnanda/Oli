<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">
                    Edit Produk
                </h2>
                <p class="text-gray-300 text-sm mt-1">Perbarui informasi produk</p>
            </div>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-all duration-200 shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 overflow-hidden shadow-xl rounded-xl border border-gray-600/40">
                <div class="p-8">
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Produk -->
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-300 mb-2">Nama Produk</label>
                                <input type="text" class="w-full bg-gray-600 border-2 border-gray-500 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder-gray-400 @error('name') border-red-500 @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" placeholder="Masukkan nama produk">
                                @error('name')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                            </div>

                            <!-- Kode Produk (Read-only) -->
                            <div>
                                <label for="code" class="block text-sm font-semibold text-gray-300 mb-2">Kode Produk</label>
                                <input type="text" class="w-full bg-gray-700 border-2 border-gray-600 text-gray-400 rounded-lg px-4 py-2.5 cursor-not-allowed" id="code" value="{{ $product->code }}" readonly>
                                <p class="mt-1 text-xs text-gray-400">Kode produk tidak dapat diubah</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <!-- Kategori -->
                            <div>
                                <label for="category_id" class="block text-sm font-semibold text-gray-300 mb-2">Kategori</label>
                                <select class="w-full bg-gray-600 border-2 border-gray-500 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('category_id') border-red-500 @enderror" id="category_id" name="category_id">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                            </div>

                            <!-- Supplier -->
                            <div>
                                <label for="supplier_id" class="block text-sm font-semibold text-gray-300 mb-2">Supplier</label>
                                <select class="w-full bg-gray-600 border-2 border-gray-500 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('supplier_id') border-red-500 @enderror" id="supplier_id" name="supplier_id">
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ $product->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                @error('supplier_id')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <!-- Harga Beli -->
                            <div>
                                <label for="buy_price" class="block text-sm font-semibold text-gray-300 mb-2">Harga Beli</label>
                                <input type="text" class="w-full bg-gray-600 border-2 border-gray-500 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder-gray-400 @error('buy_price') border-red-500 @enderror" id="buy_price" name="buy_price" value="{{ old('buy_price', number_format($product->buy_price, 0, ',', '.')) }}" placeholder="20.000" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1')">
                                @error('buy_price')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                                <p class="mt-1 text-xs text-gray-400">Gunakan titik sebagai pemisah ribuan</p>
                            </div>

                            <!-- Harga Jual -->
                            <div>
                                <label for="sell_price" class="block text-sm font-semibold text-gray-300 mb-2">Harga Jual</label>
                                <input type="text" class="w-full bg-gray-600 border-2 border-gray-500 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder-gray-400 @error('sell_price') border-red-500 @enderror" id="sell_price" name="sell_price" value="{{ old('sell_price', number_format($product->sell_price, 0, ',', '.')) }}" placeholder="25.000" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1')">
                                @error('sell_price')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                                <p class="mt-1 text-xs text-gray-400">Gunakan titik sebagai pemisah ribuan</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <!-- Stok Saat Ini -->
                            <div>
                                <label for="stock" class="block text-sm font-semibold text-gray-300 mb-2">Stok Saat Ini</label>
                                <input type="number" class="w-full bg-gray-600 border-2 border-gray-500 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder-gray-400 @error('stock') border-red-500 @enderror" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" placeholder="100">
                                @error('stock')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                            </div>

                            <!-- Stok Minimum -->
                            <div>
                                <label for="minimum_stock" class="block text-sm font-semibold text-gray-300 mb-2">Stok Minimum</label>
                                <input type="number" class="w-full bg-gray-600 border-2 border-gray-500 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder-gray-400 @error('minimum_stock') border-red-500 @enderror" id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock', $product->minimum_stock) }}" placeholder="10">
                                @error('minimum_stock')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Gambar Produk -->
                        <div class="mt-6">
                            <label for="image" class="block text-sm font-semibold text-gray-300 mb-2">Ganti Gambar Produk</label>
                            <input type="file" class="w-full bg-gray-600 border-2 border-gray-500 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600 file:cursor-pointer @error('image') border-red-500 @enderror" id="image" name="image" accept="image/*">
                            @error('image')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                            
                            @if($product->image)
                                <div class="mt-4 p-4 bg-gray-600/40 border border-gray-500/40 rounded-lg">
                                    <p class="text-sm font-medium text-gray-300 mb-2">Gambar Saat Ini:</p>
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-40 h-40 object-cover rounded-lg shadow-lg border-2 border-gray-500">
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-4 mt-8 pt-6 border-t border-gray-600/40">
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update Produk
                            </button>
                            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-semibold transition-all duration-200 shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>