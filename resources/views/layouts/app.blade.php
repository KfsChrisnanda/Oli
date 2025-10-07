<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Olinol') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @stack('styles')
        
        <style>
            [x-cloak] { 
                display: none !important; 
            }
            
            :root {
                --primary-gradient: linear-gradient(135deg, #64748b 0%, #475569 100%);
                --secondary-gradient: linear-gradient(135deg, #94a3b8 0%, #64748b 100%);
                --success-gradient: linear-gradient(135deg, #059669 0%, #047857 100%);
                --warning-gradient: linear-gradient(135deg, #d97706 0%, #b45309 100%);
                --dark-gradient: linear-gradient(135deg, #475569 0%, #334155 100%);
                --glass-bg: rgba(100, 116, 139, 0.25);
                --glass-border: rgba(148, 163, 184, 0.18);
                --card-dark: linear-gradient(135deg, #475569 0%, #334155 100%);
                --card-light: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            }
            
            body {
                background: linear-gradient(135deg, #334155 0%, #1e293b 25%, #0f172a 50%, #020617 100%);
                background-attachment: fixed;
                font-family: 'Inter', sans-serif;
                min-height: 100vh;
                position: relative;
            }
            
            body::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image: 
                    radial-gradient(circle at 20% 20%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(99, 102, 241, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 40% 70%, rgba(139, 92, 246, 0.1) 0%, transparent 50%);
                pointer-events: none;
                z-index: -1;
            }
            
            .glassmorphism {
                background: var(--glass-bg);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid var(--glass-border);
            }
            
            .sidebar-gradient {
                background: linear-gradient(to bottom right, #374151 0%, #1f2937 50%, #111827 100%);
                position: relative;
                overflow: hidden;
            }
            
            .sidebar-gradient::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.02"><circle cx="30" cy="30" r="1"/></g></svg>');
                pointer-events: none;
            }
            
            .nav-item {
                position: relative;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                margin: 0.25rem 0.75rem;
                border-radius: 12px;
                overflow: hidden;
            }
            
            .nav-item::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent);
                transition: left 0.5s;
            }
            
            .nav-item:hover::before {
                left: 100%;
            }
            
            .nav-item:hover {
                transform: translateX(4px);
                background: rgba(255, 255, 255, 0.05);
            }
            
            .nav-item.active {
                background: linear-gradient(to right, rgba(59, 130, 246, 0.2), rgba(59, 130, 246, 0.1));
                border-left: 3px solid rgb(59, 130, 246);
                transform: translateX(4px);
            }
            
            .header-glass {
                background: var(--glass-bg);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border-bottom: 1px solid var(--glass-border);
            }
            
            .content-card {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.3);
                border-radius: 20px;
                box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            }
            
            .floating-action {
                position: fixed;
                bottom: 2rem;
                right: 2rem;
                background: var(--secondary-gradient);
                border-radius: 50%;
                width: 60px;
                height: 60px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
                transition: all 0.3s ease;
                z-index: 1000;
            }
            
            .floating-action:hover {
                transform: scale(1.1) rotate(5deg);
                box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
            }
            
            .brand-logo {
                background: var(--success-gradient);
                border-radius: 16px;
                padding: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 1rem;
                position: relative;
                overflow: hidden;
            }
            
            .brand-logo::after {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
                animation: shimmer 3s infinite;
            }
            
            @keyframes shimmer {
                0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
                100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
            }
            
            .role-badge {
                background: var(--warning-gradient);
                color: #333;
                padding: 0.25rem 0.75rem;
                border-radius: 20px;
                font-size: 0.75rem;
                font-weight: 600;
                letter-spacing: 0.5px;
                text-transform: uppercase;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }
            
            .dropdown-glass {
                background: var(--glass-bg);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid var(--glass-border);
                border-radius: 12px;
                box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div 
            x-data="{ sidebarOpen: false, sidebarExpanded: true }" 
            class="relative min-h-screen lg:flex"
            @keydown.escape.window="sidebarOpen = false"
        >
            <!-- Sidebar -->
            <aside 
                class="sidebar-gradient text-white flex-col justify-between transition-all duration-500 ease-in-out hidden lg:flex"
                :class="sidebarExpanded ? 'w-72' : 'w-20'">
                @include('layouts.navigation')
            </aside>

            <!-- Mobile Sidebar Overlay -->
            <div 
                class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden" 
                x-show="sidebarOpen" 
                @click="sidebarOpen = false"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            ></div>
            
            <!-- Mobile Sidebar -->
            <aside 
                class="sidebar-gradient text-white w-72 flex-col justify-between transition-all duration-500 ease-in-out fixed inset-y-0 left-0 z-30 transform lg:hidden"
                x-show="sidebarOpen"
                x-transition:enter="transition ease-in-out duration-300"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in-out duration-300"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                >
                <div x-data="{ sidebarExpanded: true }" class="flex flex-col h-full">
                    @include('layouts.navigation')
                </div>
            </aside>
            
            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Header -->
                <header class="header-glass flex items-center justify-between p-4 lg:px-8 sticky top-0 z-10">
                    <div class="flex items-center space-x-4">
                        <button @click="sidebarOpen = true" class="text-white hover:text-gray-200 focus:outline-none lg:hidden transition-colors bg-gray-700/50 rounded-lg p-2">
                            <i class="bi bi-list text-2xl"></i>
                        </button>
                        
                        <button @click="sidebarExpanded = !sidebarExpanded" class="hidden lg:block text-white hover:text-gray-200 focus:outline-none transition-colors bg-gray-700/50 rounded-lg p-2">
                            <i class="bi bi-layout-sidebar-inset text-xl"></i>
                        </button>
                    </div>
                    
                    @isset($header)
                        <div class="font-bold text-xl text-slate-800 leading-tight">
                            {{ $header }}
                        </div>
                    @endisset

                    <div class="flex items-center space-x-4">
                        <!-- Role Badge -->
                        <span class="role-badge">
                            {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}
                        </span>
                        
                        <!-- User Dropdown -->
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-medium rounded-xl text-gray-600 bg-white/50 hover:bg-white/70 focus:outline-none transition ease-in-out duration-150 backdrop-blur-sm">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-8 h-8 bg-gradient-to-r from-gray-600 to-gray-700 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <div>{{ Auth::user()->name }}</div>
                                    </div>
                                    <div class="ms-2">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <div class="dropdown-glass">
                                    <x-dropdown-link :href="route('profile.edit')" class="hover:bg-white/20 transition-colors"> 
                                        <i class="bi bi-person mr-2"></i>{{ __('Profile') }} 
                                    </x-dropdown-link>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="hover:bg-white/20 transition-colors text-red-600">
                                            <i class="bi bi-box-arrow-right mr-2"></i>{{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </header>

                <!-- Main Content Area -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
                    <!-- Low Stock Alert - DARK THEME -->
                    @if(session('low_stock_alert'))
                        <div class="mb-6 bg-gradient-to-r from-red-900 to-red-800 border border-red-700 rounded-xl p-4 shadow-2xl animate-pulse" id="lowStockAlert">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-exclamation-triangle text-red-300 text-xl animate-bounce"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="font-semibold text-red-100 text-sm">Peringatan Stok Menipis!</h4>
                                        <p class="text-red-200 text-sm mt-1">{{ session('low_stock_alert') }}</p>
                                        <a href="{{ route('products.index') }}" class="text-red-300 hover:text-red-100 text-sm font-medium underline mt-1 inline-block">
                                            <i class="bi bi-arrow-right-circle mr-1"></i>Kelola Produk
                                        </a>
                                    </div>
                                </div>
                                <button onclick="document.getElementById('lowStockAlert').style.display='none'" class="text-red-300 hover:text-red-100 transition-colors">
                                    <i class="bi bi-x-lg text-lg"></i>
                                </button>
                            </div>
                        </div>
                    @endif                    <div class="bg-transparent p-8 min-h-full">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
        
        <!-- Floating Action Button (Optional) -->
        <div class="floating-action" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
            <i class="bi bi-arrow-up text-xl"></i>
        </div>
        
        <script src="{{ asset('js/toast.js') }}"></script>
        @stack('scripts')
        @livewireScripts
    </body>
</html>