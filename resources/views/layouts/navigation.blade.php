<!-- Brand Logo -->
<a href="{{ route('dashboard') }}" class="flex items-center p-4 bg-gray-800/30 h-16 border-b border-gray-600/20" :class="sidebarExpanded ? 'justify-start' : 'justify-center'">
    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg">
         <i class="bi bi-box-seam-fill text-white"></i>
    </div>
    <span class="font-semibold text-white text-lg ml-3 transition-opacity duration-200" :class="sidebarExpanded ? 'opacity-100' : 'opacity-0 absolute'">Olinol</span>
</a>

<nav class="flex-1 px-4 py-4 space-y-1">
    <p class="px-4 text-xs text-gray-300 uppercase tracking-wider mb-4 font-semibold" x-show="sidebarExpanded">Menu</p>

    @if(auth()->user()->isAdmin() || auth()->user()->isKasir())
        <x-side-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <i class="bi bi-speedometer2"></i>
            <span class="transition-opacity duration-200" :class="sidebarExpanded ? 'opacity-100' : 'opacity-0'">Dashboard</span>
        </x-side-nav-link>
    @endif

    @if(auth()->user()->isAdmin() || auth()->user()->isKasir())
        <x-side-nav-link :href="route('pos.index')" :active="request()->routeIs('pos.index')">
            <i class="bi bi-cart4"></i>
            <span class="transition-opacity duration-200" :class="sidebarExpanded ? 'opacity-100' : 'opacity-0'">Kasir (POS)</span>
        </x-side-nav-link>
    @endif
    
    <x-side-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.index')">
        <i class="bi bi-clock-history"></i>
        <span class="transition-opacity duration-200" :class="sidebarExpanded ? 'opacity-100' : 'opacity-0'">Histori Transaksi</span>
    </x-side-nav-link>

    @if(auth()->user()->isAdmin() || auth()->user()->isPenjagaGudang())
        <p class="px-4 pt-6 text-xs text-gray-300 uppercase tracking-wider mb-2 font-semibold" x-show="sidebarExpanded">Manajemen</p>
        
        <x-side-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
            <i class="bi bi-box-seam"></i>
            <span class="transition-opacity duration-200" :class="sidebarExpanded ? 'opacity-100' : 'opacity-0'">Produk</span>
            @php
                $lowStockCount = \App\Models\Product::whereColumn('stock', '<=', 'minimum_stock')->count();
            @endphp
            @if($lowStockCount > 0)
                <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1 font-semibold animate-pulse" :class="sidebarExpanded ? 'opacity-100' : 'opacity-0'">
                    {{ $lowStockCount }}
                </span>
            @endif
        </x-side-nav-link>
        
        <x-side-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')">
            <i class="bi bi-truck"></i>
            <span class="transition-opacity duration-200" :class="sidebarExpanded ? 'opacity-100' : 'opacity-0'">Supplier</span>
        </x-side-nav-link>
    @endif
</nav>

<div class="p-4 border-t border-gray-600/20">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-gradient-to-br from-gray-600 to-gray-800 rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg">
            <span class="text-white font-semibold text-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
        </div>
        <div class="transition-opacity duration-200" :class="sidebarExpanded ? 'opacity-100' : 'opacity-0'">
            <p class="font-medium text-white text-sm">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-300 capitalize">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</p>
            <a href="{{ route('profile.edit') }}" class="text-xs text-gray-400 hover:text-white transition-colors">Lihat Profil</a>
        </div>
    </div>
</div>