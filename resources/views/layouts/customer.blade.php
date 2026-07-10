<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>drgMaskapai - {{ $title ?? 'Pesan Tiket Pesawat' }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        * { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-white text-black">
    {{-- HEADER TRANSPARAN di atas gambar --}}
    <header class="absolute top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-[72px]">
                {{-- Kiri: Logo --}}
                <a href="{{ route('customer.home') }}" class="flex items-center gap-2 flex-shrink-0">
                    <div class="w-[34px] h-[34px] bg-white/20 backdrop-blur rounded-xl flex items-center justify-center border border-white/30">
                        <svg class="w-[18px] h-[18px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </div>
                    <span class="text-white font-extrabold text-lg tracking-tight drop-shadow">drg<span class="text-white/70">.</span>Maskapai</span>
                </a>

                {{-- Tengah: Search Bar --}}
                <div class="hidden md:block flex-1 max-w-md mx-6">
                    <div class="relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" placeholder="Cari penerbangan..." readonly onclick="window.location.href='{{ route('customer.search') }}'"
                               class="w-full pl-10 pr-4 h-10 bg-white/15 backdrop-blur border border-white/30 rounded-full text-sm text-white placeholder-white/70 cursor-pointer hover:bg-white/25 transition-all focus:outline-none">
                    </div>
                </div>

                {{-- Kanan: Auth / Profile --}}
                <div class="flex items-center gap-1.5">
                    @guest
                        <a href="{{ route('login') }}" class="px-5 py-2 text-sm font-semibold text-white hover:bg-white/10 rounded-full transition-all">Masuk</a>
                        <a href="{{ route('register') }}" class="px-5 py-2 bg-white text-blue-700 text-sm font-bold rounded-full hover:bg-blue-50 transition-all shadow">Daftar</a>
                    @else
                        {{-- Profile Dropdown --}}
                        <div class="relative" x-data="{ open: false }" x-cloak>
                            <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 pl-3 pr-2 py-1.5 rounded-full hover:bg-white/10 transition-all border border-white/20">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/avatars/' . Auth::user()->avatar) }}" alt="" class="w-8 h-8 rounded-full object-cover border-2 border-white/50">
                                @else
                                    <div class="w-8 h-8 bg-white/30 backdrop-blur rounded-full flex items-center justify-center">
                                        <span class="text-sm font-bold text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                                <span class="hidden sm:block text-sm font-semibold text-white max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-white/80" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" 
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-200 py-2 z-50"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95">
                                <div class="px-4 py-3 border-b border-slate-100">
                                    <p class="text-sm font-bold text-black">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('customer.profile') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-black hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Profile
                                </a>
                                <a href="{{ route('customer.bookings') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-black hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    Booking
                                </a>
                                <div class="border-t border-slate-100 mt-2 pt-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors w-full">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </header>

    {{-- Main --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-blue-900 border-t border-blue-800 mt-16 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Top section: grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 pb-12 border-b border-blue-700">
                {{-- Brand --}}
                <div class="sm:col-span-2 lg:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5 text-blue-900" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white">drg<span class="text-blue-300">.</span>Maskapai</span>
                    </div>
                    <p class="text-sm text-blue-200 leading-relaxed mb-4">
                        Platform pemesanan tiket pesawat terpercaya di Indonesia. Kami membantu Anda terbang ke berbagai destinasi impian dengan harga terbaik.
                    </p>
                    {{-- Social media icons --}}
                    <div class="flex items-center gap-3">
                        <a href="#" class="w-9 h-9 bg-blue-800 hover:bg-blue-700 rounded-xl flex items-center justify-center transition-all" aria-label="Instagram">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 bg-blue-800 hover:bg-blue-700 rounded-xl flex items-center justify-center transition-all" aria-label="Facebook">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 bg-blue-800 hover:bg-blue-700 rounded-xl flex items-center justify-center transition-all" aria-label="Twitter">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 bg-blue-800 hover:bg-blue-700 rounded-xl flex items-center justify-center transition-all" aria-label="YouTube">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h3 class="text-white font-bold text-sm mb-4 uppercase tracking-wider">Navigasi</h3>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('customer.home') }}" class="text-sm text-blue-200 hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="{{ route('customer.search') }}" class="text-sm text-blue-200 hover:text-white transition-colors">Cari Penerbangan</a></li>
                        <li><a href="{{ route('customer.bookings') }}" class="text-sm text-blue-200 hover:text-white transition-colors">Booking Saya</a></li>
                    </ul>
                </div>

                {{-- Destinasi --}}
                <div>
                    <h3 class="text-white font-bold text-sm mb-4 uppercase tracking-wider">Destinasi Populer</h3>
                    <ul class="space-y-2.5">
                        <li><span class="text-sm text-blue-200">Jakarta → Bali</span></li>
                        <li><span class="text-sm text-blue-200">Jakarta → Surabaya</span></li>
                        <li><span class="text-sm text-blue-200">Jakarta → Medan</span></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <h3 class="text-white font-bold text-sm mb-4 uppercase tracking-wider">Kontak</h3>
                    <ul class="space-y-2.5">
                        <li class="flex items-center gap-2 text-sm text-blue-200">
                            <svg class="w-4 h-4 text-blue-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            hello@drgmaskapai.com
                        </li>
                        <li class="flex items-center gap-2 text-sm text-blue-200">
                            <svg class="w-4 h-4 text-blue-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            +62 21 1234 5678
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Bottom --}}
            <div class="flex flex-col sm:flex-row items-center justify-between pt-8 gap-4">
                <p class="text-sm text-blue-300">&copy; {{ date('Y') }} drgMaskapai. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="text-sm text-blue-300 hover:text-white transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="text-sm text-blue-300 hover:text-white transition-colors">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>