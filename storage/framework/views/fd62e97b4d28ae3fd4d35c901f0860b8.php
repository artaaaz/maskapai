<?php $__env->startSection('content'); ?>
<style>
* { box-sizing: border-box; }

/* ===== CARD MODERN ===== */
.card-modern { border-radius: 16px; transition: all 0.25s ease; }
.card-modern:hover { transform: translateY(-2px); box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08); }

/* ===== DESTINATION CARD ===== */
.dest-card { border-radius: 20px; overflow: hidden; transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; position: relative; }
.dest-card:hover { transform: translateY(-6px); box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12); }
.dest-card .dest-thumb { width: 100%; height: 200px; object-fit: cover; transition: transform 0.5s ease; }
.dest-card:hover .dest-thumb { transform: scale(1.08); }
.dest-card .dest-overlay { position: absolute; bottom: 0; left: 0; right: 0; padding: 20px; background: linear-gradient(to top, rgba(0,0,0,0.7), transparent); color: white; }

/* ===== FLOATING SEARCH PANEL ===== */
.floating-panel {
    position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(0.95);
    width: 480px; max-width: 95vw; max-height: 85vh; background: white; border-radius: 24px;
    box-shadow: 0 25px 80px rgba(0,0,0,0.25); z-index: 10000;
    display: none; opacity: 0; transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
    overflow: hidden; flex-direction: column;
}
.floating-panel.active { display: flex; opacity: 1; transform: translate(-50%, -50%) scale(1); }
.floating-panel-backdrop {
    position: fixed; inset: 0; background: rgba(0,0,0,0.45); backdrop-filter: blur(6px);
    z-index: 9999; display: none;
}
.floating-panel-backdrop.active { display: block; }

/* ===== PASSENGER MODAL ===== */
.passenger-modal {
    position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(0.95);
    width: 480px; max-width: 95vw; background: white; border-radius: 24px;
    box-shadow: 0 25px 80px rgba(0,0,0,0.25); z-index: 10000;
    display: none; opacity: 0; transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
    overflow: hidden;
}
.passenger-modal.active { display: block; opacity: 1; transform: translate(-50%, -50%) scale(1); }

/* ===== SEARCH INPUT IN PANEL ===== */
.panel-search-input {
    width: 100%; height: 52px; padding: 0 20px 0 48px;
    border: 2px solid #e5e7eb; border-radius: 999px;
    font-size: 15px; color: #111827; outline: none; transition: border-color 0.15s;
    background: #f9fafb;
}
.panel-search-input:focus { border-color: #2563EB; background: white; }
.panel-search-input::placeholder { color: #9ca3af; }

/* ===== DESTINATION CHIP ===== */
.dest-chip {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 18px; border-radius: 999px; font-size: 13px; font-weight: 500;
    background: #f3f4f6; color: #374151; cursor: pointer; transition: all 0.15s;
    border: 1px solid transparent; white-space: nowrap;
}
.dest-chip:hover { background: #eff6ff; border-color: #bfdbfe; color: #2563EB; }
.dest-chip.active { background: #2563EB; color: white; border-color: #2563EB; }

/* ===== AIRPORT RESULT ITEM ===== */
.airport-item {
    display: flex; align-items: center; gap: 14px; padding: 12px 16px;
    border-radius: 14px; cursor: pointer; transition: background 0.12s;
}
.airport-item:hover { background: #f3f4f6; }
.airport-item .code-badge {
    width: 48px; height: 48px; border-radius: 12px; background: #eff6ff;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 13px; color: #2563EB; flex-shrink: 0;
}

/* ===== STEPPER ===== */
.stepper { display: flex; align-items: center; gap: 12px; }
.stepper-btn {
    width: 36px; height: 36px; border-radius: 50%; border: 1.5px solid #d1d5db;
    background: white; display: flex; align-items: center; justify-content: center;
    font-size: 18px; font-weight: 600; color: #374151; cursor: pointer; transition: all 0.12s;
}
.stepper-btn:hover { border-color: #2563EB; color: #2563EB; background: #eff6ff; }
.stepper-btn:active { transform: scale(0.9); }
.stepper-btn:disabled { opacity: 0.3; cursor: not-allowed; }

/* ===== CABIN SEGMENTED ===== */
.cabin-btn {
    flex: 1; padding: 10px 8px; border-radius: 12px; border: 1.5px solid #e5e7eb;
    background: white; cursor: pointer; transition: all 0.12s; text-align: center;
    font-size: 12px; font-weight: 500; color: #374151;
}
.cabin-btn:hover { border-color: #bfdbfe; background: #fafbff; }
.cabin-btn.active { border-color: #2563EB; background: #eff6ff; color: #2563EB; font-weight: 600; }

/* ===== SECTION TITLE ===== */
.section-title { font-size: 1.5rem; font-weight: 700; color: #111827; position: relative; display: inline-block; }
.section-title::after { content: ''; position: absolute; bottom: -6px; left: 0; width: 36px; height: 3px; background: #2563EB; border-radius: 2px; }

/* ===== RECENT CHIP ===== */
.recent-chip {
    display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px;
    background: #f3f4f6; border-radius: 100px; font-size: 13px; color: #374151;
    cursor: pointer; transition: all 0.2s ease; border: 1px solid transparent;
}
.recent-chip:hover { background: #eff6ff; border-color: #bfdbfe; color: #2563EB; }

/* ===== PROMO CARD ===== */
.promo-card {
    border-radius: 20px; overflow: hidden; transition: all 0.3s ease;
    background: linear-gradient(135deg, #1e40af, #3b82f6);
    color: white; position: relative;
}
.promo-card:hover { transform: translateY(-4px); box-shadow: 0 16px 48px rgba(37, 99, 235, 0.25); }
.promo-card .promo-bg-pattern {
    position: absolute; inset: 0; opacity: 0.1;
    background-image: radial-gradient(circle at 25% 25%, white 1px, transparent 1px);
    background-size: 20px 20px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .dest-card .dest-thumb { height: 160px; }
    .hero-heading { font-size: 2rem !important; }
    .floating-panel, .passenger-modal { width: 100vw; max-height: 100vh; border-radius: 0; top: 0; left: 0; transform: none; }
    .floating-panel.active, .passenger-modal.active { transform: none; }
}

/* ===== FOCUS ===== */
*:focus-visible { outline: 2px solid #2563EB; outline-offset: 2px; border-radius: 4px; }
input[type="date"]::-webkit-calendar-picker-indicator { cursor: pointer; padding: 2px; }

/* ===== SCROLLBAR ===== */
.panel-scroll::-webkit-scrollbar { width: 4px; }
.panel-scroll::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }

/* ===== SEARCH CARD MODERN ===== */
.search-card-modern {
    background: white;
    border-radius: 24px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.06), 0 1px 4px rgba(0,0,0,0.04);
    transition: box-shadow 0.3s ease;
}
.search-card-modern:hover {
    box-shadow: 0 8px 40px rgba(0,0,0,0.1);
}

/* ===== SEARCH FIELD ===== */
.search-field {
    position: relative;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 16px;
    transition: all 0.2s ease;
    cursor: pointer;
}
.search-field:hover {
    border-color: #93c5fd;
    background: #f0f5ff;
}
.search-field:focus-within {
    border-color: #3b82f6;
    background: white;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* ===== SWAP BUTTON ===== */
.swap-btn {
    width: 40px; height: 40px;
    border-radius: 50%;
    background: white;
    border: 1.5px solid #e2e8f0;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    z-index: 2;
}
.swap-btn:hover {
    background: #eff6ff;
    border-color: #3b82f6;
    color: #3b82f6;
    transform: rotate(180deg);
}

/* ===== SEARCH BUTTON ===== */
.search-btn-modern {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    border-radius: 16px;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 16px rgba(37, 99, 235, 0.3);
}
.search-btn-modern:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 24px rgba(37, 99, 235, 0.4);
    background: linear-gradient(135deg, #1d4ed8, #1e40af);
}
</style>


<div class="floating-panel-backdrop" id="panelBackdrop"></div>


<div class="floating-panel" id="airportPanel" role="dialog" aria-modal="true" aria-label="Pilih kota atau bandara">
    <div class="flex items-center justify-between px-6 pt-6 pb-3">
        <h2 class="text-2xl sm:text-3xl font-bold text-black">Pilih Kota atau Bandara</h2>
        <button type="button" onclick="closeAirportPanel()" class="p-2 hover:bg-gray-100 rounded-full transition" aria-label="Tutup">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div class="px-6 pb-3">
        <div class="relative">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" id="panelSearchInput" class="panel-search-input" placeholder="Masukkan nama kota atau bandara" autocomplete="off" aria-label="Cari kota atau bandara">
        </div>
    </div>
    <div class="flex-1 overflow-y-auto panel-scroll px-6 pb-6">
        
        <div id="panelPopularSection">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Destinasi Populer</p>
            <div class="flex flex-wrap gap-2 mb-5" id="popularChips">
                <?php if(isset($airports)): ?>
                    <?php $__currentLoopData = $airports->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="dest-chip" data-city="<?php echo e($ap->city); ?>" onclick="selectPopularDest('<?php echo e($ap->city); ?>')"><?php echo e($ap->city); ?> (<?php echo e($ap->iata_code); ?>)</span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <span class="text-sm text-gray-400">Memuat data bandara...</span>
                <?php endif; ?>
            </div>
        </div>

        
        <div id="panelRecentSection" class="mb-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Pencarian Terakhir</p>
            <div id="panelRecentList"></div>
        </div>

        
        <div id="panelResults" style="display:none;">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Hasil Pencarian</p>
            <div id="panelResultsList"></div>
        </div>
    </div>
</div>


<div class="passenger-modal" id="passengerModal" role="dialog" aria-modal="true" aria-label="Pilih penumpang dan kelas">
    <div class="flex items-center justify-between px-6 pt-6 pb-3 border-b border-gray-100">
        <h2 class="text-xl font-bold text-black">Penumpang & Kelas</h2>
        <button type="button" onclick="closePassengerModal()" class="p-2 hover:bg-gray-100 rounded-full transition" aria-label="Tutup">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div class="px-6 py-5 space-y-5">
        
        <div class="flex items-center justify-between">
            <div>
                <p class="font-semibold text-black text-sm">Dewasa</p>
                <p class="text-xs text-gray-400">12 tahun ke atas</p>
            </div>
            <div class="stepper">
                <button type="button" class="stepper-btn" onclick="adjustPax('adult', -1)" id="adultMinus" aria-label="Kurangi dewasa">−</button>
                <span id="adultCount" class="font-bold text-base w-6 text-center text-black">1</span>
                <button type="button" class="stepper-btn" onclick="adjustPax('adult', 1)" id="adultPlus" aria-label="Tambah dewasa">+</button>
            </div>
        </div>
        
        <div class="flex items-center justify-between">
            <div>
                <p class="font-semibold text-black text-sm">Anak</p>
                <p class="text-xs text-gray-400">2 - 11 tahun</p>
            </div>
            <div class="stepper">
                <button type="button" class="stepper-btn" onclick="adjustPax('child', -1)" id="childMinus" aria-label="Kurangi anak">−</button>
                <span id="childCount" class="font-bold text-base w-6 text-center text-black">0</span>
                <button type="button" class="stepper-btn" onclick="adjustPax('child', 1)" id="childPlus" aria-label="Tambah anak">+</button>
            </div>
        </div>
        
        <div class="flex items-center justify-between">
            <div>
                <p class="font-semibold text-black text-sm">Bayi</p>
                <p class="text-xs text-gray-400">Dibawah 2 tahun</p>
            </div>
            <div class="stepper">
                <button type="button" class="stepper-btn" onclick="adjustPax('infant', -1)" id="infantMinus" aria-label="Kurangi bayi">−</button>
                <span id="infantCount" class="font-bold text-base w-6 text-center text-black">0</span>
                <button type="button" class="stepper-btn" onclick="adjustPax('infant', 1)" id="infantPlus" aria-label="Tambah bayi">+</button>
            </div>
        </div>

        
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Kelas Kabin</p>
            <div class="flex gap-2" id="cabinSelector">
                <?php $__currentLoopData = config('travel_class'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button type="button" class="cabin-btn <?php echo e($key == 'economy' ? 'active' : ''); ?>" data-class="<?php echo e($key); ?>" onclick="selectCabin('<?php echo e($key); ?>')"><?php echo e($class['label']); ?></button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <div class="sticky bottom-0 px-6 py-4 border-t border-gray-100 bg-white">
        <button type="button" onclick="savePassengerModal()" class="w-full h-12 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition text-sm">Simpan</button>
    </div>
</div>




<section class="relative min-h-[520px] md:min-h-[600px] lg:min-h-[660px] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0">
        <img src="<?php echo e(asset('images/2.jpg')); ?>" alt="Latar belakang destinasi penerbangan" class="w-full h-full object-cover" loading="eager">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-900/85 via-blue-800/65 to-blue-900/75"></div>
    </div>
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle at 25% 25%, white 1px, transparent 1px); background-size: 40px 40px;"></div>
    <div class="cloud-anim cloud-anim-1" aria-hidden="true"></div>
    <div class="cloud-anim cloud-anim-2" aria-hidden="true"></div>
    <div class="cloud-anim cloud-anim-3" aria-hidden="true"></div>

    <div class="relative w-full max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 z-10 py-16 md:py-20 text-center">
        
        <div class="mb-6 md:mb-8">
            <h1 class="hero-heading text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight">
                <?php if(auth()->guard()->check()): ?> Selamat datang, <?php echo e(Auth::user()->name); ?> <?php else: ?> Terbang ke <br>Destinasi Impian <?php endif; ?>
            </h1>
            <p class="text-white/80 text-base md:text-lg mt-3 md:mt-4 mx-auto max-w-2xl">
                <?php if(auth()->guard()->check()): ?> Pesan tiket pesawat dengan mudah dan cepat. Harga terbaik, proses simpel. <?php else: ?> Temukan tiket pesawat terbaik untuk perjalanan Anda. Harga bersahabat, proses mudah. <?php endif; ?>
            </p>
        </div>

        
        <div class="search-card-modern p-4 md:p-6 w-full mx-auto max-w-[1100px]" role="search" aria-label="Cari penerbangan">
            <form action="<?php echo e(route('customer.flights.results')); ?>" method="GET" id="heroSearchForm" novalidate>
                
                <div class="flex items-center gap-5 mb-4 pb-4 border-b border-gray-100">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="trip_type_radio" value="one_way" checked onchange="setTripType('one_way')" class="w-4 h-4 text-blue-600 accent-blue-600">
                        <span class="text-sm font-medium text-gray-700">Sekali Jalan</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="trip_type_radio" value="round_trip" onchange="setTripType('round_trip')" class="w-4 h-4 text-blue-600 accent-blue-600">
                        <span class="text-sm font-medium text-gray-700">Pulang Pergi</span>
                    </label>
                    <input type="hidden" name="trip_type" id="tripTypeInput" value="one_way">
                </div>

                
                <div class="flex flex-col lg:flex-row items-stretch lg:items-start gap-3">
                    
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 flex-1">
                        
                        <div class="flex-1 min-w-0" id="fromField">
                            <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Dari</label>
                            <div class="search-field">
                                <input type="text" id="fromInput" readonly
                                       class="w-full h-12 px-4 bg-transparent border-none rounded-2xl text-sm text-black font-medium focus:outline-none cursor-pointer"
                                       placeholder="Pilih kota" aria-label="Kota asal" autocomplete="off"
                                       onclick="openAirportPanel('from')">
                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                            <select name="departure_airport_id" id="departureSelect" class="sr-only" aria-hidden="true">
                                <option value="">Pilih</option>
                                <?php $__currentLoopData = $airports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($ap->id); ?>" data-city="<?php echo e($ap->city); ?>" data-code="<?php echo e($ap->iata_code); ?>"><?php echo e($ap->city); ?> (<?php echo e($ap->iata_code); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        
                        <div class="flex justify-center sm:mt-6">
                            <div class="swap-btn" role="button" tabindex="0" aria-label="Tukar asal dan tujuan" onclick="swapRoutes()" onkeydown="if(event.key==='Enter')swapRoutes()">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                            </div>
                        </div>

                        
                        <div class="flex-1 min-w-0" id="toField">
                            <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Ke</label>
                            <div class="search-field">
                                <input type="text" id="toInput" readonly
                                       class="w-full h-12 px-4 bg-transparent border-none rounded-2xl text-sm text-black font-medium focus:outline-none cursor-pointer"
                                       placeholder="Pilih kota" aria-label="Kota tujuan" autocomplete="off"
                                       onclick="openAirportPanel('to')">
                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                            <select name="arrival_airport_id" id="arrivalSelect" class="sr-only" aria-hidden="true">
                                <option value="">Pilih</option>
                                <?php $__currentLoopData = $airports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($ap->id); ?>" data-city="<?php echo e($ap->city); ?>" data-code="<?php echo e($ap->iata_code); ?>"><?php echo e($ap->city); ?> (<?php echo e($ap->iata_code); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 flex-shrink-0">
                        
                        <div class="min-w-[130px]">
                            <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Pergi</label>
                            <div class="search-field">
                                <input type="date" id="departDate" name="departure_date" required value="<?php echo e(now()->format('Y-m-d')); ?>"
                                       class="w-full h-12 px-4 bg-transparent border-none rounded-2xl text-sm text-black font-medium focus:outline-none"
                                       aria-label="Tanggal keberangkatan">
                            </div>
                        </div>

                        
                        <div class="min-w-[130px] relative">
                            <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Pulang</label>
                            <div class="search-field">
                                <input type="date" id="returnDate" value="<?php echo e(now()->addDay()->format('Y-m-d')); ?>"
                                       class="w-full h-12 px-4 bg-transparent border-none rounded-2xl text-sm text-black font-medium focus:outline-none"
                                       aria-label="Tanggal kepulangan">
                                <div id="returnOverlay" class="absolute inset-0 bg-gray-100/90 rounded-2xl flex items-center justify-between px-4 cursor-pointer" onclick="activateReturnDate()">
                                    <span class="text-sm text-gray-400 font-medium">Sekali jalan</span>
                                    <span class="text-[10px] bg-gray-200 text-gray-500 font-semibold px-2 py-0.5 rounded-full">Aktifkan</span>
                                </div>
                            </div>
                            <input type="hidden" name="return_date" id="returnDateHidden">
                        </div>

                        
                        <div class="min-w-[140px]">
                            <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Penumpang</label>
                            <button type="button" id="passengerBtn"
                                    class="search-field w-full h-12 px-4 flex items-center justify-between gap-1 text-sm text-black font-medium cursor-pointer"
                                    onclick="openPassengerModal()"
                                    aria-label="Pilih jumlah penumpang dan kelas"
                                    aria-haspopup="true"
                                    aria-expanded="false">
                                <span id="passengerLabel" class="truncate">1 Penumpang</span>
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <input type="hidden" name="passenger_count" id="passengerCountInput" value="1">
                            <input type="hidden" name="travel_class" id="travelClassInput" value="economy">
                        </div>

                        
                        <div class="min-w-[120px]">
                            <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5 opacity-0">Cari</label>
                            <button type="submit" class="search-btn-modern w-full h-12 flex items-center justify-center gap-2 text-sm" aria-label="Cari penerbangan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Cari
                            </button>
                        </div>
                    </div>
                </div>

                
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mr-1">Pencarian Terakhir:</span>
                        <div id="recentSearches" class="flex flex-wrap items-center gap-2"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>


<section class="py-10 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="section-title mb-6">Pencarian Populer</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <?php
                // Ambil rute populer dari database real dengan harga terendah dari flight_classes
                $popularRoutes = \App\Models\Flight::select(
                        'flights.departure_airport_id',
                        'flights.arrival_airport_id',
                        \Illuminate\Support\Facades\DB::raw('MIN((SELECT MIN(fc2.price) FROM flight_classes fc2 WHERE fc2.flight_id = flights.id)) as min_price')
                    )
                    ->where('departure_time', '>=', now())
                    ->whereHas('flightClasses')
                    ->with(['departureAirport', 'arrivalAirport'])
                    ->groupBy('flights.departure_airport_id', 'flights.arrival_airport_id')
                    ->orderBy('min_price')
                    ->limit(4)
                    ->get();
            ?>
            <?php $__currentLoopData = $popularRoutes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $route): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $departure = $route->departureAirport;
                    $arrival = $route->arrivalAirport;
                    $imgIndex = $loop->index % 2 == 0 ? '1' : '2';
                    $imgUrl = asset('images/' . $imgIndex . '.jpg');
                ?>
                <a href="<?php echo e(route('customer.flights.results')); ?>?departure_airport_id=<?php echo e($departure->id); ?>&arrival_airport_id=<?php echo e($arrival->id); ?>&departure_date=<?php echo e(now()->format('Y-m-d')); ?>&passenger_count=1&travel_class=economy&trip_type=one_way"
                   class="dest-card block group" aria-label="Penerbangan <?php echo e($departure->city); ?> ke <?php echo e($arrival->city); ?>">
                    <img src="<?php echo e($imgUrl); ?>" alt="<?php echo e($arrival->city); ?>" class="dest-thumb" loading="lazy">
                    <div class="dest-overlay">
                        <p class="text-xs font-medium text-white/70"><?php echo e($departure->city); ?> (<?php echo e($departure->iata_code); ?>) →</p>
                        <p class="text-lg font-bold text-white"><?php echo e($arrival->city); ?> (<?php echo e($arrival->iata_code); ?>)</p>
                        <p class="text-sm font-semibold text-white mt-1">Mulai Rp<?php echo e(number_format($route->min_price, 0, ',', '.')); ?></p>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>



<?php if(auth()->guard()->check()): ?>
<section class="py-8 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="card-modern bg-white border border-gray-100 p-5 flex items-center gap-4 shadow-sm">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <div><p class="text-2xl font-bold text-black"><?php echo e($myBookings ?? 0); ?></p><p class="text-xs text-gray-500">Total Booking</p></div>
            </div>
            <div class="card-modern bg-white border border-gray-100 p-5 flex items-center gap-4 shadow-sm">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div><p class="text-2xl font-bold text-black"><?php echo e($myTrips ?? 0); ?></p><p class="text-xs text-gray-500">Selesai</p></div>
            </div>
            <div class="card-modern bg-white border border-gray-100 p-5 flex items-center gap-4 shadow-sm">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div><p class="text-2xl font-bold text-black">Rp<?php echo e(number_format($mySpent ?? 0, 0, ',', '.')); ?></p><p class="text-xs text-gray-500">Pengeluaran</p></div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>


<section class="py-14 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
            <div><h2 class="section-title">Jadwal Hari Ini</h2><p class="text-gray-500 text-sm mt-2">Penerbangan tersedia hari ini</p></div>
            <?php if(isset($allFlights) && $allFlights->count() > 0): ?>
                <span class="text-sm font-semibold text-blue-600 bg-blue-50 px-4 py-2 rounded-full"><?php echo e($allFlights->count()); ?> penerbangan</span>
            <?php endif; ?>
        </div>
        <?php if(isset($allFlights) && $allFlights->count() > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php $__currentLoopData = $allFlights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flight): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $availSeats = $flight->available_seats_count;
                    $isFull = $availSeats <= 0;
                    $cheapest = $flight->flightClasses->sortBy('price')->first();
                    $price = $cheapest ? $cheapest->price : $flight->price;
                ?>
                <div class="card-modern bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm">
                <div class="bg-blue-600 px-5 py-4">
                    <div class="flex items-center justify-between text-white">
                        <div class="flex items-center gap-2">
                            <?php if($flight->airline->logo): ?>
                                <img src="<?php echo e(asset('storage/' . $flight->airline->logo)); ?>" alt="<?php echo e($flight->airline->name); ?>" class="w-7 h-7 rounded-lg object-cover border border-white/20">
                            <?php else: ?>
                                <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center text-[10px] font-bold">
                                    <?php echo e(substr($flight->airline->name ?? 'DRG', 0, 2)); ?>

                                </div>
                            <?php endif; ?>
                            <div>
                                <p class="font-semibold text-sm"><?php echo e($flight->flight_number); ?></p>
                                <p class="text-xs text-blue-200"><?php echo e($flight->airline->name ?? ''); ?></p>
                            </div>
                        </div>
                        <?php if($isFull): ?>
                            <span class="text-xs font-bold bg-red-500/30 px-3 py-1 rounded-full">FULL</span>
                        <?php else: ?>
                            <span class="text-xs font-medium bg-white/20 px-3 py-1 rounded-full"><?php echo e($availSeats); ?> kursi</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-center"><p class="text-xl font-bold text-black"><?php echo e(\Carbon\Carbon::parse($flight->departure_time)->format('H:i')); ?></p><p class="text-xs text-gray-400"><?php echo e($flight->departureAirport->iata_code ?? ''); ?></p></div>
                        <div class="flex-1 mx-3"><div class="h-px bg-gray-200 relative"><div class="w-3 h-3 bg-blue-600 rounded-full absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"></div></div><p class="text-xs text-gray-400 text-center mt-1"><?php echo e($flight->duration_formatted); ?></p></div>
                        <div class="text-center"><p class="text-xl font-bold text-black"><?php echo e(\Carbon\Carbon::parse($flight->arrival_time)->format('H:i')); ?></p><p class="text-xs text-gray-400"><?php echo e($flight->arrivalAirport->iata_code ?? ''); ?></p></div>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div><p class="text-xs text-gray-400">Harga/orang</p><p class="text-xl font-bold text-black">Rp<?php echo e(number_format($price, 0, ',', '.')); ?></p></div>
                        <?php if($isFull): ?>
                            <span class="px-5 py-2.5 bg-gray-300 text-gray-500 font-semibold rounded-xl text-sm cursor-not-allowed">Penuh</span>
                        <?php else: ?>
                            <a href="<?php echo e(route('customer.flights.detail', $flight)); ?>?passenger_count=1&travel_class=economy" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition text-sm" aria-label="Lihat detail penerbangan <?php echo e($flight->flight_number); ?>">Detail</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="bg-white rounded-xl border border-gray-100 p-12 text-center">
            <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            </div>
            <p class="text-black font-semibold text-lg">Belum Ada Penerbangan</p>
            <p class="text-gray-400 text-sm mt-1">Gunakan form pencarian di atas untuk mencari penerbangan tersedia</p>
        </div>
        <?php endif; ?>
    </div>
</section>


<section class="py-14 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10"><h2 class="text-2xl sm:text-3xl font-bold text-black" style="display:inline-block;position:relative;">Cara Pesan</h2><p class="text-gray-500 mt-3">Cukup 3 langkah mudah</p></div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
            <div class="text-center"><div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-lg shadow-blue-600/20"><span class="text-white text-2xl font-bold">1</span></div><h3 class="font-semibold text-black text-lg">Cari Penerbangan</h3><p class="text-sm text-gray-500 mt-1">Masukkan kota asal, tujuan, dan tanggal</p></div>
            <div class="text-center"><div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-lg shadow-blue-600/20"><span class="text-white text-2xl font-bold">2</span></div><h3 class="font-semibold text-black text-lg">Pilih & Pesan</h3><p class="text-sm text-gray-500 mt-1">Pilih kursi favorit dan lakukan pemesanan</p></div>
            <div class="text-center"><div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-lg shadow-blue-600/20"><span class="text-white text-2xl font-bold">3</span></div><h3 class="font-semibold text-black text-lg">Terbang</h3><p class="text-sm text-gray-500 mt-1">Bayar dan dapatkan e-ticket, siap terbang</p></div>
        </div>
    </div>
</section>


<section class="py-14 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10"><h2 class="text-2xl sm:text-3xl font-bold text-black" style="display:inline-block;position:relative;">Kenapa Pilih Kami?</h2></div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php $features = [['icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z','title'=>'Respon Cepat','desc'=>'Booking cepat & mudah'],['icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','title'=>'Aman','desc'=>'Transaksi terjamin'],['icon'=>'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z','title'=>'Harga Terbaik','desc'=>'Harga kompetitif'],['icon'=>'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z','title'=>'24 Jam','desc'=>'Layanan konsumen']]; ?>
            <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card-modern text-center p-6 bg-white rounded-xl border border-gray-100">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mx-auto mb-4"><svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($f['icon']); ?>"/></svg></div>
                <h3 class="font-semibold text-black text-sm"><?php echo e($f['title']); ?></h3><p class="text-xs text-gray-500 mt-1"><?php echo e($f['desc']); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="py-14 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-8 md:p-12 text-center shadow-xl shadow-blue-600/20">
            <h2 class="text-2xl sm:text-3xl font-bold text-white mb-3">Siap Terbang?</h2>
            <p class="text-blue-100 text-lg max-w-lg mx-auto mb-6">Ayo pesan tiketmu sekarang dan nikmati pengalaman terbang yang menyenangkan!</p>
            <a href="<?php echo e(route('customer.flights.results')); ?>" class="inline-flex items-center gap-2 px-8 py-3 bg-white text-blue-600 font-semibold rounded-xl hover:bg-blue-50 transition shadow-lg" aria-label="Cari dan pesan penerbangan sekarang">Pesan Sekarang</a>
        </div>
    </div>
</section>


<script>
(function() {
    'use strict';

    // ===== STATE =====
    let tripType = 'one_way';
    let fromId = '', toId = '', fromName = '', toName = '', fromCode = '', toCode = '';
    let panelMode = 'from';
    let adultCount = 1, childCount = 0, infantCount = 0;
    let selectedCabin = 'economy';
    const cabinLabels = { 'economy':'Ekonomi','premium_economy':'Premium Ekonomi','business':'Bisnis','first':'First Class' };

    // ===== TRIP TYPE =====
    window.setTripType = function(type) {
        tripType = type;
        document.getElementById('tripTypeInput').value = type;
        const ret = document.getElementById('returnDate');
        const ov = document.getElementById('returnOverlay');
        if (type === 'one_way') {
            ov.style.display = 'flex'; ret.disabled = true;
            document.getElementById('returnDateHidden').removeAttribute('name');
        } else {
            ov.style.display = 'none'; ret.disabled = false;
            document.getElementById('returnDateHidden').setAttribute('name', 'return_date');
            document.getElementById('returnDateHidden').value = ret.value;
        }
    };
    window.activateReturnDate = function() {
        document.querySelector('input[name="trip_type_radio"][value="round_trip"]').click();
        setTimeout(() => document.getElementById('returnDate').showPicker?.(), 100);
    };

    // ===== AIRPORT PANEL =====
    window.openAirportPanel = function(mode) {
        panelMode = mode;
        document.getElementById('panelBackdrop').classList.add('active');
        document.getElementById('airportPanel').classList.add('active');
        document.body.style.overflow = 'hidden';
        const input = document.getElementById('panelSearchInput');
        input.value = '';
        input.focus();
        document.getElementById('panelResults').style.display = 'none';
        document.getElementById('panelPopularSection').style.display = 'block';
        document.getElementById('panelRecentSection').style.display = 'block';
        renderPanelRecent();
    };

    window.closeAirportPanel = function() {
        document.getElementById('panelBackdrop').classList.remove('active');
        document.getElementById('airportPanel').classList.remove('active');
        document.body.style.overflow = '';
    };

    document.getElementById('panelBackdrop').addEventListener('click', closeAirportPanel);

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (document.getElementById('airportPanel').classList.contains('active')) closeAirportPanel();
            if (document.getElementById('passengerModal').classList.contains('active')) closePassengerModal();
        }
    });

    // ===== PANEL SEARCH =====
    let panelDebounce = null;
    const airportSearchUrl = '<?php echo e(route('customer.airports.search')); ?>';
    document.getElementById('panelSearchInput').addEventListener('input', function() {
        const q = this.value.trim();
        clearTimeout(panelDebounce);
        if (q.length < 2) {
            document.getElementById('panelResults').style.display = 'none';
            document.getElementById('panelPopularSection').style.display = 'block';
            document.getElementById('panelRecentSection').style.display = 'block';
            return;
        }
        panelDebounce = setTimeout(() => searchAirports(q), 250);
    });

    async function searchAirports(query) {
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
            results = airports.filter(ap =>
                (ap.city && ap.city.toLowerCase().includes(localQuery)) ||
                (ap.iata_code && ap.iata_code.toLowerCase().includes(localQuery)) ||
                (ap.name && ap.name.toLowerCase().includes(localQuery))
            );
        }
        document.getElementById('panelPopularSection').style.display = 'none';
        document.getElementById('panelRecentSection').style.display = 'none';
        const container = document.getElementById('panelResults');
        const list = document.getElementById('panelResultsList');
        container.style.display = 'block';
        list.innerHTML = '';

        if (results.length === 0) {
            list.innerHTML = '<p class="text-sm text-gray-400 py-4 text-center">Tidak ada hasil untuk "' + query + '"</p>';
            return;
        }

        const seen = {};
        const unique = [];
        results.forEach(ap => {
            const key = ap.city + ap.iata_code;
            if (!seen[key]) { seen[key] = true; unique.push(ap); }
        });

        unique.slice(0, 10).forEach(ap => {
            const div = document.createElement('div');
            div.className = 'airport-item';
            div.tabIndex = 0;
            div.innerHTML = '<div class="code-badge">' + ap.iata_code + '</div><div><p class="font-medium text-sm text-black">' + ap.city + ' (' + ap.iata_code + ')</p><p class="text-xs text-gray-400">' + (ap.name || '') + '</p></div>';
            div.addEventListener('click', function() { selectAirportFromPanel(ap); });
            div.addEventListener('keydown', function(e) { if (e.key === 'Enter') selectAirportFromPanel(ap); });
            list.appendChild(div);
        });
    }

    function selectAirportFromPanel(ap) {
        if (panelMode === 'from') {
            fromId = ap.id; fromName = ap.city; fromCode = ap.iata_code;
            document.getElementById('fromInput').value = ap.city + ' (' + ap.iata_code + ')';
            document.getElementById('departureSelect').value = ap.id;
        } else {
            toId = ap.id; toName = ap.city; toCode = ap.iata_code;
            document.getElementById('toInput').value = ap.city + ' (' + ap.iata_code + ')';
            document.getElementById('arrivalSelect').value = ap.id;
        }
        saveRecentSearch();
        closeAirportPanel();
        if (panelMode === 'from') {
            setTimeout(() => openAirportPanel('to'), 300);
        }
    }

    window.selectPopularDest = function(city) {
        const airports = window._airports || [];
        const ap = airports.find(a => a.city === city || a.city.includes(city.replace('-',' ')));
        if (ap) {
            selectAirportFromPanel(ap);
        } else {
            const match = airports.find(a => a.city.toLowerCase().includes(city.toLowerCase().split('-')[0]));
            if (match) selectAirportFromPanel(match);
        }
    };

    window.swapRoutes = function() {
        const tId = fromId, tName = fromName, tCode = fromCode;
        fromId = toId; fromName = toName; fromCode = toCode;
        toId = tId; toName = tName; toCode = tCode;
        document.getElementById('fromInput').value = fromName ? fromName + ' (' + fromCode + ')' : '';
        document.getElementById('toInput').value = toName ? toName + ' (' + toCode + ')' : '';
        document.getElementById('departureSelect').value = fromId || '';
        document.getElementById('arrivalSelect').value = toId || '';
    };

    // ===== PASSENGER MODAL =====
    window.openPassengerModal = function() {
        document.getElementById('panelBackdrop').classList.add('active');
        document.getElementById('passengerModal').classList.add('active');
        document.body.style.overflow = 'hidden';
        document.getElementById('adultCount').textContent = adultCount;
        document.getElementById('childCount').textContent = childCount;
        document.getElementById('infantCount').textContent = infantCount;
        updateStepperButtons();
        document.querySelectorAll('.cabin-btn').forEach(b => {
            b.classList.toggle('active', b.dataset.class === selectedCabin);
        });
    };

    window.closePassengerModal = function() {
        document.getElementById('panelBackdrop').classList.remove('active');
        document.getElementById('passengerModal').classList.remove('active');
        document.body.style.overflow = '';
    };

    window.adjustPax = function(type, change) {
        if (type === 'adult') adultCount = Math.max(1, Math.min(9, adultCount + change));
        else if (type === 'child') childCount = Math.max(0, Math.min(9, childCount + change));
        else if (type === 'infant') infantCount = Math.max(0, Math.min(9, infantCount + change));
        document.getElementById(type + 'Count').textContent = type === 'adult' ? adultCount : (type === 'child' ? childCount : infantCount);
        updateStepperButtons();
    };

    function updateStepperButtons() {
        document.getElementById('adultMinus').disabled = adultCount <= 1;
        document.getElementById('adultPlus').disabled = adultCount >= 9;
        document.getElementById('childMinus').disabled = childCount <= 0;
        document.getElementById('childPlus').disabled = childCount >= 9;
        document.getElementById('infantMinus').disabled = infantCount <= 0;
        document.getElementById('infantPlus').disabled = infantCount >= 9;
    }

    window.selectCabin = function(key) {
        selectedCabin = key;
        document.querySelectorAll('.cabin-btn').forEach(b => b.classList.toggle('active', b.dataset.class === key));
    };

    window.savePassengerModal = function() {
        const total = adultCount + childCount + infantCount;
        document.getElementById('passengerCountInput').value = total;
        document.getElementById('travelClassInput').value = selectedCabin;
        const label = total + ' Penumpang';
        document.getElementById('passengerLabel').textContent = label;
        closePassengerModal();
    };

    // ===== RECENT SEARCH =====
    function saveRecentSearch() {
        if (!fromName || !toName) return;
        let recent = JSON.parse(localStorage.getItem('drg_recent_searches') || '[]');
        const entry = { from: fromName, to: toName, fromCode: fromCode, toCode: toCode };
        recent = recent.filter(r => !(r.from === entry.from && r.to === entry.to));
        recent.unshift(entry);
        if (recent.length > 5) recent = recent.slice(0, 5);
        localStorage.setItem('drg_recent_searches', JSON.stringify(recent));
        renderRecentSearches();
    }

    function renderRecentSearches() {
        const container = document.getElementById('recentSearches');
        if (!container) return;
        const recent = JSON.parse(localStorage.getItem('drg_recent_searches') || '[]');
        container.innerHTML = '';
        if (recent.length === 0) { container.innerHTML = '<span class="text-xs text-gray-400">Belum ada pencarian</span>'; return; }
        recent.forEach(r => {
            const chip = document.createElement('button');
            chip.type = 'button'; chip.className = 'recent-chip';
            chip.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> ' + r.from + ' → ' + r.to;
            chip.onclick = function() { fillRecent(r); };
            chip.setAttribute('aria-label', 'Pencarian terakhir: ' + r.from + ' ke ' + r.to);
            container.appendChild(chip);
        });
    }

    function renderPanelRecent() {
        const container = document.getElementById('panelRecentList');
        if (!container) return;
        const recent = JSON.parse(localStorage.getItem('drg_recent_searches') || '[]');
        container.innerHTML = '';
        if (recent.length === 0) { container.innerHTML = '<p class="text-sm text-gray-400">Belum ada pencarian</p>'; return; }
        recent.forEach(r => {
            const div = document.createElement('div');
            div.className = 'airport-item';
            div.tabIndex = 0;
            div.innerHTML = '<div class="code-badge" style="background:#f3f4f6;color:#6b7280;"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><span class="text-sm font-medium text-black">' + r.from + ' → ' + r.to + '</span>';
            div.addEventListener('click', function() { fillRecent(r); closeAirportPanel(); });
            div.addEventListener('keydown', function(e) { if (e.key === 'Enter') { fillRecent(r); closeAirportPanel(); } });
            container.appendChild(div);
        });
    }

    function fillRecent(r) {
        const fromOpt = document.querySelector('#departureSelect option[data-city="' + r.from + '"]');
        const toOpt = document.querySelector('#arrivalSelect option[data-city="' + r.to + '"]');
        if (fromOpt) { fromId = fromOpt.value; fromName = r.from; fromCode = r.fromCode; document.getElementById('fromInput').value = r.from + ' (' + r.fromCode + ')'; document.getElementById('departureSelect').value = fromId; }
        if (toOpt) { toId = toOpt.value; toName = r.to; toCode = r.toCode; document.getElementById('toInput').value = r.to + ' (' + r.toCode + ')'; document.getElementById('arrivalSelect').value = toId; }
    }

    // ===== FORM VALIDATION =====
    document.getElementById('heroSearchForm')?.addEventListener('submit', function(e) {
        const fv = document.getElementById('departureSelect').value;
        const tv = document.getElementById('arrivalSelect').value;
        if (!fv) { e.preventDefault(); alert('Silakan pilih kota asal'); return; }
        if (!tv) { e.preventDefault(); alert('Silakan pilih kota tujuan'); return; }
        if (fv === tv) { e.preventDefault(); alert('Kota asal dan tujuan tidak boleh sama'); return; }
        if (tripType === 'round_trip') document.getElementById('returnDateHidden').value = document.getElementById('returnDate').value;
    });

    // ===== COPY PROMO =====
    window.copyPromo = function(code) {
        navigator.clipboard.writeText(code).then(() => {
            alert('Kode promo ' + code + ' berhasil disalin!');
        }).catch(() => {
            // Fallback
            const ta = document.createElement('textarea');
            ta.value = code;
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            alert('Kode promo ' + code + ' berhasil disalin!');
        });
    };

    // ===== INIT =====
    document.addEventListener('DOMContentLoaded', function() {
        renderRecentSearches();
        setTripType('one_way');
        const today = new Date().toISOString().split('T')[0];
        const tomorrow = new Date(Date.now() + 86400000).toISOString().split('T')[0];
        document.getElementById('departDate').value = today;
        document.getElementById('returnDate').value = tomorrow;
    });
})();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\artaa\Downloads\maskapai-fixed-audit\maskapai-main\resources\views/customer/home.blade.php ENDPATH**/ ?>