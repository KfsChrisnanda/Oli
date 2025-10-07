<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">
                    Tambah Supplier
                </h2>
                <p class="text-gray-300 text-sm mt-1">Tambahkan supplier baru ke sistem</p>
            </div>
            <a href="{{ route('suppliers.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-all duration-200 shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 overflow-hidden shadow-xl rounded-xl border border-gray-600/40">
                <div class="p-8">
                    <form action="{{ route('suppliers.store') }}" method="POST">
                        @csrf
                        
                        <!-- Nama Supplier -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-semibold text-gray-300 mb-2">Nama Supplier</label>
                            <input type="text" class="w-full bg-gray-600 border-2 border-gray-500 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder-gray-400 @error('name') border-red-500 @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama supplier">
                            @error('name')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <!-- Telepon -->
                        <div class="mb-6">
                            <label for="phone" class="block text-sm font-semibold text-gray-300 mb-2">Telepon</label>
                            <input type="text" class="w-full bg-gray-600 border-2 border-gray-500 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder-gray-400 @error('phone') border-red-500 @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 0812-3456-7890">
                            @error('phone')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <!-- Alamat -->
                        <div class="mb-6">
                            <label for="address" class="block text-sm font-semibold text-gray-300 mb-2">Alamat</label>
                            <textarea class="w-full bg-gray-600 border-2 border-gray-500 text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder-gray-400 @error('address') border-red-500 @enderror" id="address" name="address" rows="4" placeholder="Masukkan alamat lengkap supplier">{{ old('address') }}</textarea>
                            @error('address')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-4 mt-8 pt-6 border-t border-gray-600/40">
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Supplier
                            </button>
                            <a href="{{ route('suppliers.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-semibold transition-all duration-200 shadow-lg">
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