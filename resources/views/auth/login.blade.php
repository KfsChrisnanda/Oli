<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Olinol') }} - Login</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @keyframes gradient {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }

            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }

            @keyframes pulse-glow {
                0%, 100% { opacity: 0.5; transform: scale(1); }
                50% { opacity: 0.8; transform: scale(1.05); }
            }

            @keyframes slide-in-left {
                from { 
                    opacity: 0;
                    transform: translateX(-50px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            @keyframes slide-in-right {
                from { 
                    opacity: 0;
                    transform: translateX(50px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            @keyframes fade-in {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            .animate-gradient {
                background-size: 200% 200%;
                animation: gradient 8s ease infinite;
            }

            .animate-float {
                animation: float 6s ease-in-out infinite;
            }

            .animate-pulse-glow {
                animation: pulse-glow 3s ease-in-out infinite;
            }

            .animate-slide-in-left {
                animation: slide-in-left 0.8s ease-out forwards;
            }

            .animate-slide-in-right {
                animation: slide-in-right 0.8s ease-out forwards;
            }

            .animate-fade-in {
                animation: fade-in 1s ease-out forwards;
            }

            .glass-effect {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            input:focus {
                transform: translateY(-2px);
                transition: all 0.3s ease;
            }
        </style>
    </head>
    <body class="font-sans antialiased overflow-hidden">
        <div class="min-h-screen flex relative">

            <!-- Left Side - Animated Background -->
            <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-gray-700 via-gray-800 to-gray-900 animate-gradient items-center justify-center p-12 text-white relative overflow-hidden">
                
                <!-- Decorative floating circles -->
                <div class="absolute top-20 left-20 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl animate-pulse-glow"></div>
                <div class="absolute bottom-40 right-20 w-40 h-40 bg-blue-600/10 rounded-full blur-3xl animate-pulse-glow" style="animation-delay: 1s;"></div>
                <div class="absolute top-1/2 left-1/3 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl animate-pulse-glow" style="animation-delay: 2s;"></div>

                <!-- Content -->
                <div class="z-10 space-y-8 animate-slide-in-left">
                    <!-- Animated Icon -->
                    <div class="flex justify-center mb-8">
                        <div class="relative animate-float">
                            <div class="w-24 h-24 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-2xl">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div class="absolute inset-0 bg-blue-500/20 rounded-2xl blur-xl"></div>
                        </div>
                    </div>

                    <div class="text-center">
                        <h1 class="text-5xl font-bold mb-6 leading-tight text-white">
                            Toko Oli Saya
                        </h1>
                        <p class="text-gray-400 text-lg max-w-md mx-auto leading-relaxed">
                            Sistem Kasir & Manajemen Stok
                        </p>
                    </div>
                </div>

                <!-- Animated lines -->
                <div class="absolute inset-0 opacity-5">
                    <div class="absolute h-px w-full bg-gradient-to-r from-transparent via-blue-500 to-transparent top-1/4"></div>
                    <div class="absolute h-px w-full bg-gradient-to-r from-transparent via-blue-500 to-transparent top-3/4"></div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center bg-gradient-to-br from-gray-800 via-gray-900 to-black p-8 relative overflow-hidden">
                
                <!-- Decorative elements -->
                <div class="absolute top-10 right-10 w-40 h-40 bg-blue-600/10 rounded-full blur-3xl animate-pulse-glow"></div>
                <div class="absolute bottom-10 left-10 w-32 h-32 bg-indigo-600/10 rounded-full blur-3xl animate-pulse-glow" style="animation-delay: 1.5s;"></div>

                <div class="max-w-md w-full relative z-10 animate-slide-in-right">
                    
                    <!-- Header -->
                    <div class="text-start mb-10 animate-fade-in">
                        <div class="inline-block mb-4">
                            <div class="w-12 h-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full"></div>
                        </div>
                        <h2 class="text-4xl font-bold text-white mb-3">
                            Selamat Datang Kembali
                        </h2>
                        <p class="text-gray-400 text-lg">Silakan login untuk melanjutkan.</p>
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email Input -->
                        <div class="group">
                            <x-input-label for="email" value="Email" class="text-gray-200 font-semibold mb-2" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500 group-focus-within:text-blue-400 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                    </svg>
                                </div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                    class="pl-10 block w-full rounded-xl border-2 border-gray-600 bg-gray-700 px-4 py-3.5 text-white placeholder-gray-500 
                                    focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 shadow-sm hover:border-gray-500" 
                                    placeholder="nama@email.com">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password Input -->
                        <div class="group">
                            <x-input-label for="password" value="Password" class="text-gray-200 font-semibold mb-2" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500 group-focus-within:text-blue-400 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input id="password" type="password" name="password" required autocomplete="current-password"
                                    class="pl-10 block w-full rounded-xl border-2 border-gray-600 bg-gray-700 px-4 py-3.5 text-white placeholder-gray-500 
                                    focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 shadow-sm hover:border-gray-500" 
                                    placeholder="••••••••">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Remember & Forgot -->
                        <div class="flex items-center justify-between pt-2">
                            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                                <input id="remember_me" type="checkbox" 
                                    class="rounded border-gray-600 bg-gray-700 text-blue-600 shadow-sm focus:ring-blue-500 focus:ring-offset-0 focus:ring-offset-gray-800 transition-all duration-200" 
                                    name="remember">
                                <span class="ms-2 text-sm text-gray-300 group-hover:text-white transition-colors duration-200">Ingat saya</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="text-sm font-medium text-blue-400 hover:text-blue-300 transition-colors duration-200 hover:underline" 
                                   href="{{ route('password.request') }}">
                                    Lupa password?
                                </a>
                            @endif
                        </div>

                        <!-- Login Button -->
                        <div class="pt-2">
                            <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 
                                text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl 
                                transform hover:-translate-y-0.5 transition-all duration-300 
                                focus:outline-none focus:ring-4 focus:ring-blue-500/50 
                                flex items-center justify-center group">
                                <span>Masuk</span>
                                <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Register Link -->
                        <div class="text-center pt-4">
                            <span class="text-gray-400">Belum punya akun?</span>
                            <a class="ml-1 font-semibold text-blue-400 hover:text-blue-300 transition-colors duration-200 hover:underline" 
                               href="{{ route('register') }}">
                                Daftar di sini
                            </a>
                        </div>
                    </form>

                    <!-- Footer Info -->
                    <div class="mt-10 text-center animate-fade-in" style="animation-delay: 0.3s;">
                        <p class="text-xs text-gray-500">
                            Dilindungi dengan enkripsi dan keamanan tingkat tinggi
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>