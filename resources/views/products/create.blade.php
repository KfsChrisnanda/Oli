<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-white leading-tight">Tambah Produk Baru</h2>
                <p class="text-gray-300 text-sm mt-1">Masukkan informasi produk yang akan ditambahkan</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 backdrop-blur-sm rounded-xl shadow-xl border border-gray-600/40">
                <div class="p-8">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <!-- Nama Produk -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-300 mb-2">Nama Produk</label>
                            <input type="text" 
                                   class="w-full bg-gray-600 border-2 border-gray-500 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('name') border-red-500 @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   placeholder="Masukkan nama produk">
                            @error('name')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-400 text-xs mt-1">Kode produk akan dibuat otomatis</p>
                        </div>

                        <!-- Kategori & Supplier -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="category_id" class="block text-sm font-semibold text-gray-300 mb-2">Kategori</label>
                                <select class="w-full bg-gray-600 border-2 border-gray-500 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('category_id') border-red-500 @enderror" 
                                        id="category_id" 
                                        name="category_id">
                                    <option value="" class="bg-gray-700">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" class="bg-gray-700">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="supplier_id" class="block text-sm font-semibold text-gray-300 mb-2">Supplier</label>
                                <select class="w-full bg-gray-600 border-2 border-gray-500 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('supplier_id') border-red-500 @enderror" 
                                        id="supplier_id" 
                                        name="supplier_id">
                                    <option value="" class="bg-gray-700">Pilih Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" class="bg-gray-700">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Harga Beli & Harga Jual -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="buy_price" class="block text-sm font-semibold text-gray-300 mb-2">Harga Beli</label>
                                <input type="text" 
                                       class="w-full bg-gray-600 border-2 border-gray-500 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('buy_price') border-red-500 @enderror" 
                                       id="buy_price" 
                                       name="buy_price" 
                                       value="{{ old('buy_price') }}" 
                                       placeholder="20.000" 
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1')">
                                @error('buy_price')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-gray-400 text-xs mt-1">Gunakan titik sebagai pemisah ribuan (contoh: 20.000)</p>
                            </div>
                            
                            <div>
                                <label for="sell_price" class="block text-sm font-semibold text-gray-300 mb-2">Harga Jual</label>
                                <input type="text" 
                                       class="w-full bg-gray-600 border-2 border-gray-500 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('sell_price') border-red-500 @enderror" 
                                       id="sell_price" 
                                       name="sell_price" 
                                       value="{{ old('sell_price') }}" 
                                       placeholder="25.000" 
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1')">
                                @error('sell_price')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-gray-400 text-xs mt-1">Gunakan titik sebagai pemisah ribuan (contoh: 25.000)</p>
                            </div>
                        </div>

                        <!-- Stok Saat Ini & Stok Minimum -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="stock" class="block text-sm font-semibold text-gray-300 mb-2">Stok Saat Ini</label>
                                <input type="number" 
                                       class="w-full bg-gray-600 border-2 border-gray-500 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('stock') border-red-500 @enderror" 
                                       id="stock" 
                                       name="stock" 
                                       value="{{ old('stock') }}"
                                       placeholder="0">
                                @error('stock')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="minimum_stock" class="block text-sm font-semibold text-gray-300 mb-2">Stok Minimum</label>
                                <input type="number" 
                                       class="w-full bg-gray-600 border-2 border-gray-500 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('minimum_stock') border-red-500 @enderror" 
                                       id="minimum_stock" 
                                       name="minimum_stock" 
                                       value="{{ old('minimum_stock', 10) }}"
                                       placeholder="10">
                                @error('minimum_stock')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Gambar Produk -->
                        <div>
                            <label for="image" class="block text-sm font-semibold text-gray-300 mb-2">Gambar Produk</label>
                            <input type="file" 
                                   class="w-full bg-gray-600 border-2 border-gray-500 text-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-500 file:text-white file:cursor-pointer hover:file:bg-blue-600 @error('image') border-red-500 @enderror" 
                                   id="image" 
                                   name="image">
                            @error('image')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center gap-4 pt-4 border-t border-gray-600/40">
                            <button type="submit" 
                                    class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i class="bi bi-save mr-2"></i>Simpan Produk
                            </button>
                            <a href="{{ route('products.index') }}" 
                               class="px-6 py-3 bg-gray-600 hover:bg-gray-500 text-white font-semibold rounded-lg transition-all duration-200">
                                <i class="bi bi-x-circle mr-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>