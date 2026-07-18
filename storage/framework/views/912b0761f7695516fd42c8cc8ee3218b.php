<?php $__env->startSection('content'); ?>
<style>
.flight-card {
    border-radius: 20px;
    background: white;
    border: 1px solid #e5e7eb;
    transition: all 0.25s ease;
}
.flight-card:hover {
    box-shadow: 0 8px 32px rgba(0,0,0,0.08);
    border-color: #bfdbfe;
}

.flight-card .airline-logo-placeholder {
    width: 40px; height: 40px;
    border-radius: 12px;
    background: #eff6ff;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 14px; color: #2563EB;
}

.flight-path-line {
    position: relative; height: 2px; background: #e5e7eb; flex: 1; margin: 0 12px;
}
.flight-path-line::before {
    content: '';
    position: absolute; left: 0; top: -3px;
    width: 8px; height: 8px; border-radius: 50%; background: #2563EB;
}
.flight-path-line::after {
    content: '';
    position: absolute; right: 0; top: -3px;
    width: 8px; height: 8px; border-radius: 50%; background: #2563EB;
}
.flight-path-plane {
    position: absolute; top: -6px; left: 50%; transform: translateX(-50%);
    color: #2563EB;
}

.filter-section {
    background: white;
    border-radius: 20px;
    border: 1px solid #e5e7eb;
    padding: 20px;
}

.filter-group + .filter-group {
    border-top: 1px solid #f3f4f6;
    padding-top: 16px;
    margin-top: 16px;
}

.price-slider {
    -webkit-appearance: none;
    width: 100%; height: 6px;
    border-radius: 3px;
    background: #e5e7eb;
    outline: none;
}
.price-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 20px; height: 20px;
    border-radius: 50%;
    background: #2563EB;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(37,99,235,0.3);
}

.sort-btn {
    padding: 8px 16px;
    border-radius: 100px;
    font-size: 13px;
    font-weight: 500;
    background: #f3f4f6;
    color: #374151;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.15s;
    white-space: nowrap;
}
.sort-btn:hover { background: #eff6ff; border-color: #bfdbfe; color: #2563EB; }
.sort-btn.active { background: #2563EB; color: white; border-color: #2563EB; }

.transit-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 12px;
    border-radius: 100px;
    font-size: 11px;
    font-weight: 600;
    background: #fef3c7;
    color: #92400e;
}
.transit-badge.direct {
    background: #d1fae5;
    color: #065f46;
}

@media (max-width: 768px) {
    .filter-sidebar { position: fixed; bottom: 0; left: 0; right: 0; z-index: 9999; background: white; border-radius: 20px 20px 0 0; max-height: 80vh; overflow-y: auto; transform: translateY(100%); transition: transform 0.3s ease; }
    .filter-sidebar.active { transform: translateY(0); }
    .filter-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 9998; display: none; }
    .filter-backdrop.active { display: block; }
}
</style>


<div class="filter-backdrop" id="filterBackdrop" onclick="closeFilterMobile()"></div>


<section class="relative min-h-[200px] md:min-h-[240px] flex items-center overflow-hidden">
    <div class="absolute inset-0">
        <img src="<?php echo e(asset('images/2.jpg')); ?>" alt="" class="w-full h-full object-cover" loading="eager">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-900/85 via-blue-800/65 to-blue-900/75"></div>
    </div>
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle at 25% 25%, white 1px, transparent 1px); background-size: 40px 40px;"></div>
    <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10 py-8 md:py-10">
        <h1 class="text-3xl md:text-4xl font-black text-white mb-2">Hasil Pencarian ✈️</h1>
        <p class="text-blue-100 text-sm md:text-base">Temukan penerbangan terbaik untuk perjalanan Anda</p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-10">
    
    <div class="bg-white rounded-3xl shadow-xl p-4 md:p-5 mb-8 border border-slate-100">
        <form action="<?php echo e(route('customer.flights.results')); ?>" method="GET" id="resultsSearchForm">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Dari</label>
                    <select name="departure_airport_id" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-slate-800">
                        <option value="">Pilih</option>
                        <?php $__currentLoopData = $airports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $airport): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($airport->id); ?>" <?php echo e((request('departure_airport_id', $searchParams['departure_airport_id'] ?? '') == $airport->id) ? 'selected' : ''); ?>>
                                <?php echo e($airport->city); ?> (<?php echo e($airport->iata_code); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Ke</label>
                    <select name="arrival_airport_id" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-slate-800">
                        <option value="">Pilih</option>
                        <?php $__currentLoopData = $airports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $airport): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($airport->id); ?>" <?php echo e((request('arrival_airport_id', $searchParams['arrival_airport_id'] ?? '') == $airport->id) ? 'selected' : ''); ?>>
                                <?php echo e($airport->city); ?> (<?php echo e($airport->iata_code); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Pergi</label>
                    <input type="date" name="departure_date" required value="<?php echo e(request('departure_date', $searchParams['departure_date'] ?? now()->format('Y-m-d'))); ?>" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-slate-800">
                </div>
                <div class="md:col-span-3 relative">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Pulang</label>
                    <div class="relative">
                        <input type="date" id="resultsReturnDate" name="return_date" value="<?php echo e(request('return_date', $searchParams['return_date'] ?? '')); ?>" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-slate-800">
                        <div id="resultsReturnOverlay" class="absolute inset-0 bg-slate-100/95 rounded-xl flex items-center justify-between px-3 cursor-pointer hover:bg-slate-200 transition-colors <?php echo e(request('trip_type') === 'round_trip' ? 'hidden' : ''); ?>" onclick="activateResultsReturn()">
                            <span class="text-xs font-semibold text-slate-500">Sekali Jalan</span>
                            <span class="text-[10px] bg-blue-100 text-blue-700 font-bold px-2 py-0.5 rounded-full uppercase">Aktifkan</span>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Penumpang</label>
                    <select name="passenger_count" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-slate-800">
                        <?php for($i=1;$i<=9;$i++): ?>
                            <option value="<?php echo e($i); ?>" <?php echo e((request('passenger_count', $searchParams['passenger_count'] ?? 1) == $i) ? 'selected' : ''); ?>><?php echo e($i); ?> Penumpang</option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="md:col-span-1 flex items-end">
                    <input type="hidden" name="trip_type" value="<?php echo e(request('trip_type', $searchParams['trip_type'] ?? 'one_way')); ?>">
                    <input type="hidden" name="travel_class" value="<?php echo e(request('travel_class', $searchParams['travel_class'] ?? 'economy')); ?>">
                    <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-md text-sm flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Cari
                    </button>
                </div>
            </div>
        </form>
    </div>

    
    <?php if(isset($flights) && isset($searchParams)): ?>
    <div class="flex flex-col lg:flex-row gap-6 pb-16">
        
        <div class="lg:w-72 flex-shrink-0">
            
            <div class="hidden lg:block filter-section sticky top-28">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-black text-lg">Filter</h3>
                    <button onclick="clearFilters()" class="text-xs text-blue-600 hover:text-blue-700 font-semibold">Reset</button>
                </div>

                <form id="filterForm" method="GET" action="<?php echo e(route('customer.flights.results')); ?>">
                    <?php $__currentLoopData = request()->except(['airline_id', 'max_price', 'sort_by', 'departure_time_from', 'departure_time_to', 'max_duration']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(is_array($value)): ?>
                            <?php $__currentLoopData = $value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <input type="hidden" name="<?php echo e($key); ?>[]" value="<?php echo e($v); ?>">
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <input type="hidden" name="<?php echo e($key); ?>" value="<?php echo e($value); ?>">
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    
                    <div class="filter-group">
                        <p class="text-xs font-bold text-slate-500 uppercase mb-3">Urutkan</p>
                        <div class="flex flex-wrap gap-2">
                            <?php
                                $sorts = [
                                    'time_asc' => 'Paling Awal',
                                    'price_asc' => 'Termurah',
                                    'price_desc' => 'Termahal',
                                    'duration_asc' => 'Tercepat',
                                ];
                                $currentSort = request('sort_by', 'time_asc');
                            ?>
                            <?php $__currentLoopData = $sorts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button type="button" class="sort-btn <?php echo e($currentSort === $key ? 'active' : ''); ?>" onclick="setSort('<?php echo e($key); ?>')"><?php echo e($label); ?></button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <input type="hidden" name="sort_by" id="sortByInput" value="<?php echo e($currentSort); ?>">
                        </div>
                    </div>

                    
                    <div class="filter-group">
                        <p class="text-xs font-bold text-slate-500 uppercase mb-3">Harga Maksimal</p>
                        <?php
                            $maxPrice = (int) request('max_price', 5000000);
                        ?>
                        <input type="range" class="price-slider" id="priceSlider" name="max_price" min="500000" max="10000000" step="100000" value="<?php echo e($maxPrice); ?>" oninput="updatePriceLabel(this.value)">
                        <div class="flex justify-between mt-2">
                            <span class="text-xs text-slate-500">Rp500rb</span>
                            <span class="text-sm font-bold text-blue-600" id="priceLabel">Rp<?php echo e(number_format($maxPrice, 0, ',', '.')); ?></span>
                            <span class="text-xs text-slate-500">Rp10jt</span>
                        </div>
                    </div>

                    
                    <div class="filter-group">
                        <p class="text-xs font-bold text-slate-500 uppercase mb-3">Maskapai</p>
                        <div class="space-y-2 max-h-40 overflow-y-auto">
                            <?php $__currentLoopData = $airlines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $airline): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="airline_id" value="<?php echo e($airline->id); ?>" 
                                    <?php echo e(in_array((string)$airline->id, (array)request('airline_id', [])) ? 'checked' : ''); ?>

                                    onchange="submitFilters()" class="w-4 h-4 text-blue-600 rounded accent-blue-600">
                                <span class="text-sm text-slate-700"><?php echo e($airline->name); ?></span>
                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    
                    <div class="filter-group">
                        <p class="text-xs font-bold text-slate-500 uppercase mb-3">Jam Berangkat</p>
                        <div class="space-y-2">
                            <?php
                                $timeRanges = [
                                    ['label' => '00:00 - 06:00', 'from' => '00:00', 'to' => '06:00'],
                                    ['label' => '06:00 - 12:00', 'from' => '06:00', 'to' => '12:00'],
                                    ['label' => '12:00 - 18:00', 'from' => '12:00', 'to' => '18:00'],
                                    ['label' => '18:00 - 24:00', 'from' => '18:00', 'to' => '24:00'],
                                ];
                                $selectedFrom = request('departure_time_from');
                                $selectedTo = request('departure_time_to');
                            ?>
                            <?php $__currentLoopData = $timeRanges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $range): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="time_range" value="<?php echo e($range['from']); ?>-<?php echo e($range['to']); ?>"
                                    <?php echo e(($selectedFrom === $range['from'] && $selectedTo === $range['to']) ? 'checked' : ''); ?>

                                    onchange="setTimeRange('<?php echo e($range['from']); ?>', '<?php echo e($range['to']); ?>')" class="w-4 h-4 text-blue-600 accent-blue-600">
                                <span class="text-sm text-slate-700"><?php echo e($range['label']); ?></span>
                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <input type="hidden" name="departure_time_from" id="timeFromInput" value="<?php echo e($selectedFrom ?? ''); ?>">
                            <input type="hidden" name="departure_time_to" id="timeToInput" value="<?php echo e($selectedTo ?? ''); ?>">
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition shadow-md">Terapkan Filter</button>
                </form>
            </div>

            
            <button onclick="openFilterMobile()" class="lg:hidden w-full py-3 bg-white border border-slate-200 rounded-xl font-semibold text-slate-700 shadow-sm flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filter
            </button>

            
            <div class="filter-sidebar lg:hidden" id="mobileFilter">
                <div class="sticky top-0 bg-white border-b border-slate-100 px-5 py-4 flex items-center justify-between">
                    <h3 class="font-bold text-black">Filter</h3>
                    <button onclick="closeFilterMobile()" class="p-1.5 hover:bg-slate-100 rounded-lg">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="p-5">
                    
                    <form id="mobileFilterForm" method="GET" action="<?php echo e(route('customer.flights.results')); ?>">
                        <?php $__currentLoopData = request()->except(['airline_id', 'max_price', 'sort_by', 'departure_time_from', 'departure_time_to', 'max_duration']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(is_array($value)): ?>
                                <?php $__currentLoopData = $value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <input type="hidden" name="<?php echo e($key); ?>[]" value="<?php echo e($v); ?>">
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <input type="hidden" name="<?php echo e($key); ?>" value="<?php echo e($value); ?>">
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <div class="filter-group">
                            <p class="text-xs font-bold text-slate-500 uppercase mb-3">Urutkan</p>
                            <div class="flex flex-wrap gap-2">
                                <?php $__currentLoopData = $sorts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <button type="button" class="sort-btn <?php echo e($currentSort === $key ? 'active' : ''); ?>" onclick="setSortMobile('<?php echo e($key); ?>')"><?php echo e($label); ?></button>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <input type="hidden" name="sort_by" id="sortByInputMobile" value="<?php echo e($currentSort); ?>">
                            </div>
                        </div>

                        <div class="filter-group">
                            <p class="text-xs font-bold text-slate-500 uppercase mb-3">Harga Maksimal</p>
                            <input type="range" class="price-slider" id="priceSliderMobile" name="max_price" min="500000" max="10000000" step="100000" value="<?php echo e($maxPrice); ?>" oninput="updatePriceLabelMobile(this.value)">
                            <div class="flex justify-between mt-2">
                                <span class="text-xs text-slate-500">Rp500rb</span>
                                <span class="text-sm font-bold text-blue-600" id="priceLabelMobile">Rp<?php echo e(number_format($maxPrice, 0, ',', '.')); ?></span>
                                <span class="text-xs text-slate-500">Rp10jt</span>
                            </div>
                        </div>

                        <div class="filter-group">
                            <p class="text-xs font-bold text-slate-500 uppercase mb-3">Maskapai</p>
                            <div class="space-y-2 max-h-40 overflow-y-auto">
                                <?php $__currentLoopData = $airlines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $airline): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="airline_id" value="<?php echo e($airline->id); ?>" 
                                        <?php echo e(in_array((string)$airline->id, (array)request('airline_id', [])) ? 'checked' : ''); ?>

                                        onchange="submitFiltersMobile()" class="w-4 h-4 text-blue-600 rounded accent-blue-600">
                                    <span class="text-sm text-slate-700"><?php echo e($airline->name); ?></span>
                                </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <div class="filter-group">
                            <p class="text-xs font-bold text-slate-500 uppercase mb-3">Jam Berangkat</p>
                            <div class="space-y-2">
                                <?php $__currentLoopData = $timeRanges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $range): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="time_range_mobile" value="<?php echo e($range['from']); ?>-<?php echo e($range['to']); ?>"
                                        <?php echo e(($selectedFrom === $range['from'] && $selectedTo === $range['to']) ? 'checked' : ''); ?>

                                        onchange="setTimeRangeMobile('<?php echo e($range['from']); ?>', '<?php echo e($range['to']); ?>')" class="w-4 h-4 text-blue-600 accent-blue-600">
                                    <span class="text-sm text-slate-700"><?php echo e($range['label']); ?></span>
                                </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <input type="hidden" name="departure_time_from" id="timeFromInputMobile" value="<?php echo e($selectedFrom ?? ''); ?>">
                            <input type="hidden" name="departure_time_to" id="timeToInputMobile" value="<?php echo e($selectedTo ?? ''); ?>">
                        </div>

                        <button type="submit" class="w-full mt-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition shadow-md">Terapkan Filter</button>
                    </form>
                </div>
            </div>
        </div>

        
        <div class="flex-1 min-w-0">
            <?php
                $isRoundTrip = request('trip_type', $searchParams['trip_type'] ?? 'one_way') === 'round_trip';
            ?>

            <?php if($isRoundTrip): ?>
                
                <div class="mb-6">
                    <div class="flex items-center gap-2 text-slate-500 text-sm mb-4">
                        <span class="font-bold <?php echo e(!$selectedDepartureFlight ? 'text-blue-600' : ''); ?>">1. Penerbangan Pergi</span>
                        <span class="text-slate-300">➔</span>
                        <span class="font-bold <?php echo e($selectedDepartureFlight ? 'text-blue-600' : ''); ?>">2. Penerbangan Pulang</span>
                    </div>
                    <?php if($selectedDepartureFlight): ?>
                        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 mb-8 flex justify-between items-center flex-wrap gap-4">
                            <div>
                                <span class="px-2.5 py-0.5 bg-blue-600 text-white font-bold text-xs rounded-full uppercase tracking-wider">Pergi Terpilih</span>
                                <h3 class="font-black text-slate-800 mt-2 text-lg">
                                    <?php echo e($selectedDepartureFlight->departureAirport->city); ?> (<?php echo e($selectedDepartureFlight->departureAirport->iata_code); ?>) ➔ 
                                    <?php echo e($selectedDepartureFlight->arrivalAirport->city); ?> (<?php echo e($selectedDepartureFlight->arrivalAirport->iata_code); ?>)
                                </h3>
                                <p class="text-slate-500 text-sm mt-1">
                                    <?php echo e($selectedDepartureFlight->airline->name); ?> · <?php echo e($selectedDepartureFlight->flight_number); ?> · 
                                    <?php echo e($selectedDepartureFlight->departure_time->format('d M Y, H:i')); ?>

                                </p>
                            </div>
                            <a href="?<?php echo e(http_build_query(request()->except('selected_departure_id'))); ?>" class="px-5 py-2 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-bold rounded-xl text-sm transition-colors shadow-sm">Ubah</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            
            <?php
                $currentFlights = (!$isRoundTrip || !$selectedDepartureFlight) ? $flights : ($returnFlights ?? collect());
            ?>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-black text-slate-800">
                    <?php if(!$isRoundTrip || !$selectedDepartureFlight): ?>
                        Penerbangan Pergi
                    <?php else: ?>
                        Penerbangan Pulang
                    <?php endif; ?>
                    <span class="text-slate-400 font-normal text-base">(<?php echo e($currentFlights->count()); ?> pilihan)</span>
                </h2>
            </div>

            <?php if($currentFlights->count() > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $currentFlights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flight): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $dep = \Carbon\Carbon::parse($flight->departure_time);
                        $arr = \Carbon\Carbon::parse($flight->arrival_time);
                        $durationMinutes = $dep->diffInMinutes($arr);
                        $hours = intdiv($durationMinutes, 60);
                        $mins = $durationMinutes % 60;
                        $durationStr = $hours . 'j ' . $mins . 'm';
                    ?>
                    <div class="flight-card p-5">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            
                            <div class="flex items-center gap-4 flex-shrink-0">
                                <div class="airline-logo-placeholder">
                                    <?php echo e(substr($flight->airline->name ?? 'DRG', 0, 2)); ?>

                                </div>
                                <div>
                                    <p class="font-semibold text-black text-sm"><?php echo e($flight->airline->name ?? 'DRG Maskapai'); ?></p>
                                    <p class="text-xs text-slate-400"><?php echo e($flight->flight_number); ?></p>
                                </div>
                            </div>

                            
                            <div class="flex items-center flex-1 min-w-[200px]">
                                
                                <div class="text-center flex-shrink-0">
                                    <p class="text-lg font-black text-slate-800"><?php echo e($dep->format('H:i')); ?></p>
                                    <p class="text-[10px] font-bold text-slate-500 uppercase"><?php echo e($flight->departureAirport->iata_code); ?></p>
                                </div>

                                
                                <div class="flex-1 mx-2">
                                    <div class="flight-path-line relative">
                                        <div class="flight-path-plane">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-slate-400 text-center mt-1"><?php echo e($durationStr); ?></p>
                                </div>

                                
                                <div class="text-center flex-shrink-0">
                                    <p class="text-lg font-black text-slate-800"><?php echo e($arr->format('H:i')); ?></p>
                                    <p class="text-[10px] font-bold text-slate-500 uppercase"><?php echo e($flight->arrivalAirport->iata_code); ?></p>
                                </div>
                            </div>

                            
                            <div class="flex-shrink-0">
                                <?php if($flight->transit_count > 0): ?>
                                    <span class="transit-badge">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                        Transit <?php echo e($flight->transit_count); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="transit-badge direct">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Langsung
                                    </span>
                                <?php endif; ?>
                            </div>

                            
                            <div class="text-right flex-shrink-0">
                                <p class="text-xs text-slate-400 font-medium">Mulai dari</p>
                                <p class="text-xl font-black text-blue-600">Rp<?php echo e(number_format($flight->display_price ?? 0, 0, ',', '.')); ?></p>
                                <p class="text-[10px] text-slate-500 font-semibold"><?php echo e(ucfirst(str_replace('_', ' ', $flight->selected_class_name ?? 'Economy'))); ?></p>
                                <p class="text-[10px] text-slate-500 font-semibold mb-2"><?php echo e($flight->available_seats_count); ?> kursi tersedia</p>
                                
                                <?php if($isRoundTrip && !$selectedDepartureFlight): ?>
                                    <a href="?<?php echo e(http_build_query(array_merge(request()->all(), ['selected_departure_id' => $flight->id]))); ?>" class="inline-block px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-md text-sm">
                                        Pilih Pergi
                                    </a>
                                <?php elseif($isRoundTrip && $selectedDepartureFlight): ?>
                                    <a href="<?php echo e(route('customer.flights.detail', $flight)); ?>?passenger_count=<?php echo e(request('passenger_count', 1)); ?>&travel_class=<?php echo e(request('travel_class', 'economy')); ?>&trip_type=round_trip&return_date=<?php echo e(request('return_date')); ?>&return_flight_id=<?php echo e($selectedDepartureFlight->id); ?>" 
                                        class="inline-block px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-md text-sm">
                                        Pilih Kursi
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo e(route('customer.flights.detail', $flight)); ?>?passenger_count=<?php echo e(request('passenger_count', 1)); ?>&travel_class=<?php echo e(request('travel_class', 'economy')); ?>" 
                                        class="inline-block px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-md text-sm">
                                        Pilih Kursi
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-md">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Penerbangan Tidak Ditemukan</h3>
                    <p class="text-slate-500 text-sm">Coba ubah kriteria pencarian atau filter Anda</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php else: ?>
    <div class="bg-white rounded-3xl p-16 text-center border border-slate-100 shadow-md mb-16">
        <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-5">
            <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-black text-slate-800 mb-2">Cari Penerbangan</h2>
        <p class="text-slate-500 max-w-md mx-auto">Gunakan form di atas untuk mencari penerbangan yang Anda inginkan. Masukkan kota asal, tujuan, dan tanggal keberangkatan.</p>
    </div>
    <?php endif; ?>
</div>

<script>
// Results page functions
function activateResultsReturn() {
    document.getElementById('resultsReturnOverlay').classList.add('hidden');
    document.getElementById('resultsReturnDate').focus();
    document.querySelector('input[name="trip_type"]').value = 'round_trip';
}

// Filter functions
function setSort(key) {
    document.getElementById('sortByInput').value = key;
    document.getElementById('filterForm').submit();
}

function setSortMobile(key) {
    document.getElementById('sortByInputMobile').value = key;
    document.getElementById('mobileFilterForm').submit();
}

function setTimeRange(from, to) {
    document.getElementById('timeFromInput').value = from;
    document.getElementById('timeToInput').value = to;
}

function setTimeRangeMobile(from, to) {
    document.getElementById('timeFromInputMobile').value = from;
    document.getElementById('timeToInputMobile').value = to;
}

function updatePriceLabel(val) {
    document.getElementById('priceLabel').textContent = 'Rp' + parseInt(val).toLocaleString('id-ID');
}

function updatePriceLabelMobile(val) {
    document.getElementById('priceLabelMobile').textContent = 'Rp' + parseInt(val).toLocaleString('id-ID');
}

function submitFilters() {
    document.getElementById('filterForm').submit();
}

function submitFiltersMobile() {
    document.getElementById('mobileFilterForm').submit();
}

function clearFilters() {
    const params = new URLSearchParams();
    <?php $__currentLoopData = request()->except(['airline_id', 'max_price', 'sort_by', 'departure_time_from', 'departure_time_to', 'max_duration', 'page']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(!is_array($value)): ?>
            params.set('<?php echo e($key); ?>', '<?php echo e($value); ?>');
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    window.location.href = window.location.pathname + '?' + params.toString();
}

// Mobile filter
function openFilterMobile() {
    document.getElementById('mobileFilter').classList.add('active');
    document.getElementById('filterBackdrop').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeFilterMobile() {
    document.getElementById('mobileFilter').classList.remove('active');
    document.getElementById('filterBackdrop').classList.remove('active');
    document.body.style.overflow = '';
}

// Init trip type
document.addEventListener('DOMContentLoaded', function() {
    const tripType = '<?php echo e(request('trip_type', $searchParams['trip_type'] ?? 'one_way')); ?>';
    if (tripType === 'round_trip') {
        document.getElementById('resultsReturnOverlay')?.classList.add('hidden');
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\artaa\Downloads\maskapai-fixed-audit\maskapai-main\resources\views/customer/results.blade.php ENDPATH**/ ?>