<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="theme-color" content="#2563EB">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="drgMaskapai">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="drgMaskapai">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/icons/icon-152x152.png">
    <link rel="apple-touch-icon" sizes="192x192" href="/icons/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="512x512" href="/icons/icon-512x512.png">
    <link rel="icon" type="image/svg+xml" href="/icons/icon.svg">
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/icon-72x72.png">
    
    <title>drgMaskapai - <?php echo e($title ?? 'Pesan Tiket Pesawat'); ?></title>
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        * { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }

        .search-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 9999;
            display: none;
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        .search-overlay.active {
            display: block;
            opacity: 1;
        }
        .search-overlay-panel {
            max-width: 640px;
            width: 95%;
            margin: 80px auto 0;
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.2);
            transform: scale(0.97) translateY(-10px);
            opacity: 0;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            max-height: calc(100vh - 120px);
            overflow-y: auto;
        }
        .search-overlay.active .search-overlay-panel {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
        .search-overlay-input-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            border-bottom: 1px solid #f3f4f6;
        }
        .search-overlay-input-wrap input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 16px;
            font-weight: 500;
            color: #111827;
            background: transparent;
        }
        .search-overlay-input-wrap input::placeholder {
            color: #9ca3af;
        }
        .overlay-section { padding: 16px 20px; }
        .overlay-section + .overlay-section { border-top: 1px solid #f3f4f6; }
        .overlay-section-title {
            font-size: 12px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 12px;
        }
        .overlay-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 12px;
            cursor: pointer;
            transition: background 0.15s ease;
            color: #1f2937;
            font-size: 14px;
        }
        .overlay-item:hover { background: #f3f4f6; }
        .overlay-item .icon-box {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .overlay-item .icon-box.blue { background: #eff6ff; color: #2563EB; }
        .overlay-item .icon-box.amber { background: #fef3c7; color: #d97706; }
        .overlay-item .icon-box.green { background: #ecfdf5; color: #059669; }
        .overlay-item .icon-box.purple { background: #f5f3ff; color: #7c3aed; }
        .overlay-item .icon-box.gray { background: #f3f4f6; color: #6b7280; }
        .dest-thumb-sm {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
        }
        @keyframes cloudDrift {
            0% { transform: translateX(0) translateY(0); opacity: 0.15; }
            50% { transform: translateX(30px) translateY(-10px); opacity: 0.25; }
            100% { transform: translateX(0) translateY(0); opacity: 0.15; }
        }
        .cloud-anim {
            position: absolute;
            background: rgba(255,255,255,0.12);
            border-radius: 50%;
            pointer-events: none;
            animation: cloudDrift 8s ease-in-out infinite;
        }
        .cloud-anim::before, .cloud-anim::after {
            content: '';
            position: absolute;
            background: inherit;
            border-radius: 50%;
        }
        .cloud-anim-1 { width: 160px; height: 60px; top: 12%; left: 5%; animation-duration: 10s; }
        .cloud-anim-1::before { width: 60px; height: 60px; top: -25px; left: 20px; }
        .cloud-anim-1::after { width: 80px; height: 50px; top: -20px; left: 60px; }
        .cloud-anim-2 { width: 120px; height: 45px; top: 60%; right: 8%; animation-duration: 12s; animation-delay: 2s; }
        .cloud-anim-2::before { width: 45px; height: 45px; top: -18px; left: 15px; }
        .cloud-anim-2::after { width: 60px; height: 40px; top: -15px; left: 45px; }
        .cloud-anim-3 { width: 140px; height: 50px; top: 35%; left: 60%; animation-duration: 9s; animation-delay: 4s; }
        .cloud-anim-3::before { width: 50px; height: 50px; top: -20px; left: 20px; }
        .cloud-anim-3::after { width: 70px; height: 45px; top: -18px; left: 55px; }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .skeleton {
            background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 8px;
        }
        .search-overlay-panel::-webkit-scrollbar { width: 4px; }
        .search-overlay-panel::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }
        @media (max-width: 768px) {
            .search-overlay-panel { width: 100%; margin: 0; border-radius: 0; max-height: 100vh; height: 100vh; }
        }
    </style>
</head>
<body class="bg-white text-black">
    
    <div class="search-overlay" id="searchOverlay" role="dialog" aria-modal="true" aria-label="Cari penerbangan">
        <div class="search-overlay-panel">
            <div class="search-overlay-input-wrap">
                <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" id="overlaySearchInput" placeholder="Cari kota atau bandara..." autocomplete="off" aria-label="Cari kota atau bandara">
                <button type="button" onclick="closeSearchOverlay()" class="p-1.5 hover:bg-gray-100 rounded-lg transition" aria-label="Tutup pencarian">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div id="autocompleteResults" style="display:none;" class="overlay-section"></div>
            <div id="overlayDefaultContent">
                <div class="overlay-section" id="recentSearchSection">
                    <p class="overlay-section-title">Pencarian Terakhir</p>
                    <div id="recentSearchList"></div>
                </div>
                <div class="overlay-section">
                    <p class="overlay-section-title">Rute Populer</p>
                    <div class="space-y-0.5">
                        <?php
                            $popularRoutes = [
                                ['from' => 'Jakarta', 'to' => 'Bali', 'fd' => 'CGK', 'td' => 'DPS'],
                                ['from' => 'Jakarta', 'to' => 'Surabaya', 'fd' => 'CGK', 'td' => 'SUB'],
                                ['from' => 'Jakarta', 'to' => 'Lombok', 'fd' => 'CGK', 'td' => 'LOP'],
                                ['from' => 'Jakarta', 'to' => 'Medan', 'fd' => 'CGK', 'td' => 'KNO'],
                                ['from' => 'Jakarta', 'to' => 'Makassar', 'fd' => 'CGK', 'td' => 'UPG'],
                            ];
                        ?>
                        <?php $__currentLoopData = $popularRoutes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="overlay-item" tabindex="0" onclick="redirectSearch('<?php echo e($r['from']); ?>','<?php echo e($r['to']); ?>','<?php echo e($r['fd']); ?>','<?php echo e($r['td']); ?>')" onkeydown="if(event.key==='Enter')this.click()">
                            <div class="icon-box blue">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            </div>
                            <span><?php echo e($r['from']); ?> → <?php echo e($r['to']); ?></span>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <div class="overlay-section">
                    <p class="overlay-section-title">Destinasi Populer</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <?php
                            $dests = [
                                ['name' => 'Bali', 'img' => asset('images/1.jpg'), 'code' => 'DPS'],
                                ['name' => 'Lombok', 'img' => asset('images/2.jpg'), 'code' => 'LOP'],
                                ['name' => 'Yogyakarta', 'img' => asset('images/1.jpg'), 'code' => 'YIA'],
                                ['name' => 'Batam', 'img' => asset('images/2.jpg'), 'code' => 'BTH'],
                            ];
                        ?>
                        <?php $__currentLoopData = $dests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="overlay-item" tabindex="0" onclick="redirectSearch('Jakarta','<?php echo e($d['name']); ?>','CGK','<?php echo e($d['code']); ?>')" onkeydown="if(event.key==='Enter')this.click()">
                            <img src="<?php echo e($d['img']); ?>" alt="<?php echo e($d['name']); ?>" class="dest-thumb-sm" loading="lazy">
                            <div>
                                <p class="font-medium text-sm"><?php echo e($d['name']); ?> (<?php echo e($d['code']); ?>)</p>
                                <p class="text-xs text-gray-400">Dari Jakarta</p>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <div class="overlay-section">
                    <p class="overlay-section-title">Aksi Cepat</p>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="<?php echo e(route('customer.home')); ?>" class="overlay-item">
                            <div class="icon-box blue"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div>
                            <span class="text-sm">Cari Tiket Pesawat</span>
                        </a>
                        <a href="<?php echo e(route('customer.home')); ?>" class="overlay-item">
                            <div class="icon-box amber"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg></div>
                            <span class="text-sm">Promo Penerbangan</span>
                        </a>
                        <a href="<?php echo e(route('customer.home')); ?>" class="overlay-item">
                            <div class="icon-box green"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                            <span class="text-sm">Destinasi Populer</span>
                        </a>
                        <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('customer.bookings')); ?>" class="overlay-item">
                            <div class="icon-box purple"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
                            <span class="text-sm">Riwayat Booking</span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <header class="absolute top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-[72px]">
                <a href="<?php echo e(route('customer.home')); ?>" class="flex items-center gap-2 flex-shrink-0">
                    <div class="w-[34px] h-[34px] bg-white/20 backdrop-blur rounded-xl flex items-center justify-center border border-white/30">
                        <svg class="w-[18px] h-[18px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </div>
                    <span class="text-white font-extrabold text-lg tracking-tight drop-shadow">drg<span class="text-white/70">.</span>Maskapai</span>
                </a>
                <div class="hidden md:block flex-1 max-w-md mx-6">
                    <div class="relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" placeholder="Cari penerbangan..." readonly
                               onclick="openSearchOverlay()"
                               class="w-full pl-10 pr-4 h-10 bg-white/15 backdrop-blur border border-white/30 rounded-full text-sm text-white placeholder-white/70 cursor-pointer hover:bg-white/25 transition-all focus:outline-none"
                               aria-label="Cari penerbangan">
                    </div>
                </div>
                <button type="button" onclick="openSearchOverlay()" class="md:hidden p-2 text-white/80 hover:text-white" aria-label="Cari penerbangan">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
                <div class="hidden md:flex items-center gap-1">
                    <a href="<?php echo e(route('customer.home')); ?>" class="px-4 py-2 text-sm font-semibold text-white/80 hover:text-white hover:bg-white/10 rounded-full transition-all">Beranda</a>
                    <a href="<?php echo e(route('customer.flights.results')); ?>" class="px-4 py-2 text-sm font-semibold text-white/80 hover:text-white hover:bg-white/10 rounded-full transition-all">Cari Tiket</a>
                    <a href="<?php echo e(route('customer.promos')); ?>" class="px-4 py-2 text-sm font-semibold text-white/80 hover:text-white hover:bg-white/10 rounded-full transition-all">Promo</a>
                </div>
                <div class="flex items-center gap-1.5">
                    <?php if(auth()->guard()->guest()): ?>
                        <a href="<?php echo e(route('login')); ?>" class="hidden sm:block px-5 py-2 text-sm font-semibold text-white hover:bg-white/10 rounded-full transition-all">Masuk</a>
                        <a href="<?php echo e(route('register')); ?>" class="px-5 py-2 bg-white text-blue-700 text-sm font-bold rounded-full hover:bg-blue-50 transition-all shadow">Daftar</a>
                    <?php else: ?>
                        <div class="relative" x-data="{ open: false }" x-cloak>
                            <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 pl-3 pr-2 py-1.5 rounded-full hover:bg-white/10 transition-all border border-white/20">
                                <?php if(Auth::user()->avatar): ?>
                                    <img src="<?php echo e(asset('storage/avatars/' . Auth::user()->avatar)); ?>" alt="" class="w-8 h-8 rounded-full object-cover border-2 border-white/50">
                                <?php else: ?>
                                    <div class="w-8 h-8 bg-white/30 backdrop-blur rounded-full flex items-center justify-center">
                                        <span class="text-sm font-bold text-white"><?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?></span>
                                    </div>
                                <?php endif; ?>
                                <span class="hidden sm:block text-sm font-semibold text-white max-w-[100px] truncate"><?php echo e(Auth::user()->name); ?></span>
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
                                    <p class="text-sm font-bold text-black"><?php echo e(Auth::user()->name); ?></p>
                                    <p class="text-xs text-gray-500 truncate"><?php echo e(Auth::user()->email); ?></p>
                                </div>
                                <a href="<?php echo e(route('customer.profile')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-black hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Profile
                                </a>
                                <a href="<?php echo e(route('customer.bookings')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-black hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    Pesanan Saya
                                </a>
                                <div class="border-t border-slate-100 mt-2 pt-2">
                                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors w-full">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer class="bg-blue-900 border-t border-blue-800 mt-16 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 pb-12 border-b border-blue-700">
                <div class="sm:col-span-2 lg:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5 text-blue-900" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white">drg<span class="text-blue-300">.</span>Maskapai</span>
                    </div>
                    <p class="text-sm text-blue-200 leading-relaxed mb-4">Platform pemesanan tiket pesawat terpercaya di Indonesia.</p>
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
                <div>
                    <h3 class="text-white font-bold text-sm mb-4 uppercase tracking-wider">Navigasi</h3>
                    <ul class="space-y-2.5">
                        <li><a href="<?php echo e(route('customer.home')); ?>" class="text-sm text-blue-200 hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="<?php echo e(route('customer.home')); ?>" class="text-sm text-blue-200 hover:text-white transition-colors">Cari Penerbangan</a></li>
                        <li><a href="<?php echo e(route('customer.bookings')); ?>" class="text-sm text-blue-200 hover:text-white transition-colors">Booking Saya</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-bold text-sm mb-4 uppercase tracking-wider">Destinasi Populer</h3>
                    <ul class="space-y-2.5">
                        <li><span class="text-sm text-blue-200">Jakarta → Bali</span></li>
                        <li><span class="text-sm text-blue-200">Jakarta → Surabaya</span></li>
                        <li><span class="text-sm text-blue-200">Jakarta → Medan</span></li>
                    </ul>
                </div>
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
            <div class="flex flex-col sm:flex-row items-center justify-between pt-8 gap-4">
                <p class="text-sm text-blue-300">&copy; <?php echo e(date('Y')); ?> drgMaskapai. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="text-sm text-blue-300 hover:text-white transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="text-sm text-blue-300 hover:text-white transition-colors">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
    (function() {
        'use strict';
        // Provide fallback for airports data - default to empty array if not passed
        window._airports = <?php echo json_encode($airports ?? [], 15, 512) ?>;
        if (!window._airports || !Array.isArray(window._airports)) {
            window._airports = [];
        }
        window._airportMap = {};
        (window._airports || []).forEach(function(ap) {
            const key = (ap.city + ' ' + ap.iata_code + ' ' + ap.name).toLowerCase();
            window._airportMap[ap.iata_code] = ap;
        });

        function getRecentSearches() {
            try { return JSON.parse(localStorage.getItem('drg_recent_searches') || '[]'); }
            catch(e) { return []; }
        }

        function saveRecentSearch(entry) {
            let recent = getRecentSearches();
            recent = recent.filter(function(r) { return !(r.from === entry.from && r.to === entry.to); });
            recent.unshift(entry);
            if (recent.length > 5) recent = recent.slice(0, 5);
            localStorage.setItem('drg_recent_searches', JSON.stringify(recent));
            renderRecentSearches();
        }

        function renderRecentSearches() {
            const container = document.getElementById('recentSearchList');
            if (!container) return;
            const recent = getRecentSearches();
            if (recent.length === 0) {
                container.innerHTML = '<p class="text-sm text-gray-400">Belum ada pencarian</p>';
                return;
            }
            container.innerHTML = '';
            recent.forEach(function(r) {
                const div = document.createElement('div');
                div.className = 'overlay-item';
                div.tabIndex = 0;
                div.setAttribute('role', 'button');
                div.innerHTML = '<div class="icon-box gray"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><span>' + r.from + ' → ' + r.to + '</span>';
                div.addEventListener('click', function() { redirectSearch(r.from, r.to, r.fromCode || '', r.toCode || ''); });
                div.addEventListener('keydown', function(e) { if (e.key === 'Enter') this.click(); });
                container.appendChild(div);
            });
        }

        window.openSearchOverlay = function() {
            const overlay = document.getElementById('searchOverlay');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
            const input = document.getElementById('overlaySearchInput');
            setTimeout(function() { input.focus(); }, 100);
            renderRecentSearches();
        };

        window.closeSearchOverlay = function() {
            const overlay = document.getElementById('searchOverlay');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
            document.getElementById('overlaySearchInput').value = '';
            document.getElementById('autocompleteResults').style.display = 'none';
            document.getElementById('overlayDefaultContent').style.display = 'block';
        };

        document.getElementById('searchOverlay').addEventListener('click', function(e) {
            if (e.target === this) closeSearchOverlay();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && document.getElementById('searchOverlay').classList.contains('active')) {
                closeSearchOverlay();
            }
        });

        window.redirectSearch = function(from, to, fromCode, toCode) {
            saveRecentSearch({ from: from, to: to, fromCode: fromCode, toCode: toCode });
            let url = '<?php echo e(route("customer.home")); ?>';
            const params = new URLSearchParams();
            const airports = window._airports || [];
            const fromAirport = airports.find(function(ap) { return ap.iata_code === fromCode || ap.city === from; });
            const toAirport = airports.find(function(ap) { return ap.iata_code === toCode || ap.city === to; });
            if (fromAirport) params.set('departure_airport_id', fromAirport.id);
            if (toAirport) params.set('arrival_airport_id', toAirport.id);
            params.set('departure_date', new Date().toISOString().split('T')[0]);
            params.set('passenger_count', '1');
            params.set('travel_class', 'economy');
            params.set('trip_type', 'one_way');
            closeSearchOverlay();
            window.location.href = url + '?' + params.toString();
        };

        let debounceTimer = null;
        document.getElementById('overlaySearchInput').addEventListener('input', function() {
            const query = this.value.trim();
            clearTimeout(debounceTimer);
            if (query.length < 2) {
                document.getElementById('autocompleteResults').style.display = 'none';
                document.getElementById('overlayDefaultContent').style.display = 'block';
                return;
            }
            debounceTimer = setTimeout(function() { performSearch(query); }, 300);
        });

        const airportSearchUrl = '<?php echo e(route('customer.airports.search')); ?>';

        async function performSearch(query) {
            const q = query.trim();
            let results = [];

            try {
                const response = await fetch(airportSearchUrl + '?q=' + encodeURIComponent(q));
                if (response.ok) {
                    results = await response.json();
                }
            } catch (error) {
                const localQuery = q.toLowerCase();
                const airports = window._airports || [];
                results = airports.filter(function(ap) {
                    return (ap.city && ap.city.toLowerCase().includes(localQuery)) ||
                           (ap.iata_code && ap.iata_code.toLowerCase().includes(localQuery)) ||
                           (ap.name && ap.name.toLowerCase().includes(localQuery));
                });
            }
            const container = document.getElementById('autocompleteResults');
            container.style.display = results.length > 0 ? 'block' : 'none';
            document.getElementById('overlayDefaultContent').style.display = results.length > 0 ? 'none' : 'block';
            container.innerHTML = '';
            if (results.length === 0) {
                container.innerHTML = '<div class="p-4 text-center text-sm text-gray-400">Tidak ada hasil untuk "' + query + '"</div>';
                return;
            }
            const seen = {};
            const citySuggestions = [];
            results.forEach(function(ap) {
                const key = ap.city + ap.iata_code;
                if (!seen[key]) { seen[key] = true; citySuggestions.push(ap); }
            });
            const limited = citySuggestions.slice(0, 8);
            container.innerHTML = '<p class="overlay-section-title" style="padding:0 0 8px 0;">Saran Kota & Bandara</p>';
            limited.forEach(function(ap) {
                const div = document.createElement('div');
                div.className = 'overlay-item';
                div.tabIndex = 0;
                div.innerHTML = '<div class="icon-box blue"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div><div><p class="font-medium text-sm">' + ap.city + ' (' + ap.iata_code + ')</p><p class="text-xs text-gray-400">' + (ap.name || '') + '</p></div>';
                div.addEventListener('click', function() {
                    const fromAirport = airports.find(function(a) { return a.iata_code === 'CGK'; });
                    if (fromAirport && fromAirport.city !== ap.city) {
                        redirectSearch(fromAirport.city, ap.city, 'CGK', ap.iata_code);
                    } else if (fromAirport && fromAirport.city === ap.city) {
                        const toAlt = airports.find(function(a) { return a.iata_code === 'DPS'; });
                        if (toAlt) { redirectSearch(ap.city, toAlt.city, ap.iata_code, 'DPS'); }
                    } else {
                        const altFrom = airports.find(function(a) { return a.id !== ap.id; });
                        if (altFrom) { redirectSearch(altFrom.city, ap.city, altFrom.iata_code, ap.iata_code); }
                    }
                });
                div.addEventListener('keydown', function(e) { if (e.key === 'Enter') this.click(); });
                container.appendChild(div);
            });
            const allBtn = document.createElement('div');
            allBtn.className = 'overlay-item';
            allBtn.tabIndex = 0;
            allBtn.innerHTML = '<div class="icon-box gray"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div><span>Cari semua penerbangan ke <strong>' + query + '</strong></span>';
            allBtn.addEventListener('click', function() {
                closeSearchOverlay();
                window.location.href = '<?php echo e(route("customer.home")); ?>';
            });
            allBtn.addEventListener('keydown', function(e) { if (e.key === 'Enter') this.click(); });
            container.appendChild(allBtn);
        }

        document.getElementById('overlaySearchInput').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query.length > 0) {
                    closeSearchOverlay();
                    window.location.href = '<?php echo e(route("customer.home")); ?>';
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() { renderRecentSearches(); });
    })();

    // ===== PWA SERVICE WORKER REGISTRATION =====
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            // Avoid double registration
            if (navigator.serviceWorker.controller) {
                console.log('ServiceWorker already controlling the page');
                return;
            }
            navigator.serviceWorker.register('/sw.js').then(function(registration) {
                console.log('ServiceWorker registered');
            }).catch(function(err) {
                console.log('ServiceWorker registration failed: ', err);
            });
        });
    }
    </script>

    
    <?php if (isset($component)) { $__componentOriginale49165d24e23a99ce9bef721914cd27c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale49165d24e23a99ce9bef721914cd27c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.install-button','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('install-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale49165d24e23a99ce9bef721914cd27c)): ?>
<?php $attributes = $__attributesOriginale49165d24e23a99ce9bef721914cd27c; ?>
<?php unset($__attributesOriginale49165d24e23a99ce9bef721914cd27c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale49165d24e23a99ce9bef721914cd27c)): ?>
<?php $component = $__componentOriginale49165d24e23a99ce9bef721914cd27c; ?>
<?php unset($__componentOriginale49165d24e23a99ce9bef721914cd27c); ?>
<?php endif; ?>
</body>
</html><?php /**PATH C:\Users\artaa\Downloads\maskapai-fixed-audit\maskapai-main\resources\views/layouts/customer.blade.php ENDPATH**/ ?>