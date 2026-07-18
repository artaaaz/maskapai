<?php $__env->startSection('content'); ?>
<style>
.seat-map {
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 20px;
}
.seat-row {
    display: flex;
    align-items: center;
    gap: 4px;
}
.seat-row-label {
    width: 30px;
    font-size: 12px;
    font-weight: 700;
    color: #6b7280;
    text-align: center;
}
.seat {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    border: 2px solid #d1d5db;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 700;
    color: #374151;
    cursor: pointer;
    transition: all 0.15s ease;
    position: relative;
}
.seat:hover:not(.booked):not(.unavailable) {
    border-color: #3b82f6;
    background: #eff6ff;
    transform: scale(1.05);
}
.seat.selected {
    border-color: #2563eb;
    background: #2563eb;
    color: white;
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}
.seat.booked, .seat.unavailable {
    border-color: #e5e7eb;
    background: #f3f4f6;
    color: #d1d5db;
    cursor: not-allowed;
    position: relative;
}
.seat.booked::after {
    content: '✗';
    position: absolute;
    font-size: 14px;
    color: #ef4444;
    font-weight: bold;
}
.seat.emergency {
    border-color: #f59e0b;
}
.seat.emergency::before {
    content: '⚠';
    position: absolute;
    top: -6px;
    right: -6px;
    font-size: 10px;
}
.seat-gap {
    width: 20px;
    flex-shrink: 0;
}
.seat-legend {
    display: flex;
    gap: 20px;
    justify-content: center;
    padding: 16px;
}
.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: #6b7280;
}
.legend-box {
    width: 20px;
    height: 20px;
    border-radius: 4px;
    border: 2px solid #d1d5db;
}
.legend-box.available { background: white; border-color: #d1d5db; }
.legend-box.selected { background: #2563eb; border-color: #2563eb; }
.legend-box.booked { background: #f3f4f6; border-color: #e5e7eb; }
</style>

<div class="bg-gradient-to-br from-blue-700 via-blue-800 to-indigo-900 min-h-[280px] flex items-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PHBhdGggZD0iTTM2IDE4bDEyIDZ2MTJsLTEyIDYtMTItNlYyNGwxMi02eiIvPjwvZz48L2c+PC9zdmc+')"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10 py-8">
        <div class="flex items-center gap-2 mb-4">
            <a href="<?php echo e(route('customer.flights.detail', $flight)); ?>" class="text-white/60 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <span class="text-white/50 text-xs font-medium">Kembali ke detail penerbangan</span>
        </div>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">Pilih Kursi</h1>
                <p class="text-white/70 text-sm mt-1"><?php echo e($flight->departureAirport->city); ?> (<?php echo e($flight->departureAirport->iata_code); ?>) → <?php echo e($flight->arrivalAirport->city); ?> (<?php echo e($flight->arrivalAirport->iata_code); ?>)</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1.5 bg-white/20 backdrop-blur rounded-full text-white text-xs font-bold">
                    <?php echo e($flight->flight_number); ?>

                </span>
                <span class="px-3 py-1.5 bg-yellow-400/20 backdrop-blur rounded-full text-yellow-300 text-xs font-bold">
                    <?php echo e(ucfirst(str_replace('_', ' ', $selectedClassName))); ?>

                </span>
            </div>
        </div>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-[40px] relative z-20 pb-16">
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
        
        <div class="p-4 md:p-6 border-b border-slate-100">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Pilih Kursi Anda</h2>
                    <p class="text-sm text-slate-500">Pilih <?php echo e($passengerCount); ?> kursi untuk penumpang</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-slate-500">Kelas:</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-lg">
                        <?php echo e(ucfirst(str_replace('_', ' ', $selectedClassName))); ?>

                    </span>
                </div>
            </div>
        </div>

        
        <div class="seat-legend border-b border-slate-100">
            <div class="legend-item">
                <div class="legend-box available"></div>
                <span>Tersedia</span>
            </div>
            <div class="legend-item">
                <div class="legend-box selected"></div>
                <span>Dipilih</span>
            </div>
            <div class="legend-item">
                <div class="legend-box booked"></div>
                <span>Terisi</span>
            </div>
        </div>

        
        <form action="<?php echo e(route('customer.flight-detail.store-seats', $flight)); ?>" method="POST" id="seatForm">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="flight_class_id" value="<?php echo e($selectedFlightClass->id); ?>">
            <input type="hidden" name="passenger_count" value="<?php echo e($passengerCount); ?>">
            <input type="hidden" name="travel_class" value="<?php echo e($selectedClassName); ?>">
            
            
            <div id="selectedSeatsContainer"></div>

            
            <div class="flex justify-center pt-6 pb-2">
                <div class="w-32 h-16 bg-slate-100 rounded-t-full border-2 border-b-0 border-slate-300 flex items-center justify-center">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kokpit</span>
                </div>
            </div>

            
            <div class="seat-map max-w-md mx-auto">
                <?php $__empty_1 = true; $__currentLoopData = $groupedSeats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row => $seatsInRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="seat-row">
                        <div class="seat-row-label"><?php echo e($row); ?></div>
                        <?php $col = 0; ?>
                        <?php $__currentLoopData = $seatsInRow; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            
                            <?php if($col === 3): ?>
                                <div class="seat-gap"></div>
                            <?php endif; ?>
                            <div class="seat <?php echo e($seat->status === 'booked' ? 'booked' : ''); ?> <?php echo e(in_array($seat->seat_number, old('seat_numbers', [])) ? 'selected' : ''); ?>"
                                 data-seat-number="<?php echo e($seat->seat_number); ?>"
                                 data-status="<?php echo e($seat->status); ?>"
                                 onclick="toggleSeat(this)"
                                 title="<?php echo e($seat->seat_number); ?> - <?php echo e($seat->status === 'booked' ? 'Terisi' : 'Tersedia'); ?>">
                                <?php echo e($seat->seat_number); ?>

                            </div>
                            <?php $col++; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-8 text-slate-400">
                        Tidak ada kursi tersedia untuk kelas <?php echo e(ucfirst(str_replace('_', ' ', $selectedClassName))); ?>.
                    </div>
                <?php endif; ?>
            </div>

            
            <div class="px-6 pb-4 text-center">
                <p id="selectedSeatsInfo" class="text-sm text-slate-500">Belum ada kursi dipilih</p>
            </div>

            
            <div class="p-4 md:p-6 border-t border-slate-100 bg-slate-50">
                <button type="submit" id="confirmSeatsBtn"
                    class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg text-sm disabled:bg-slate-300 disabled:cursor-not-allowed"
                    disabled>
                    Pilih Kursi Terlebih Dahulu
                </button>
                <p class="text-xs text-slate-400 text-center mt-2">Kursi akan disimpan dan kamu akan diarahkan ke halaman booking</p>
            </div>
        </form>
    </div>
</div>

<script>
let selectedSeats = [];
const maxSeats = <?php echo e($passengerCount); ?>;

function toggleSeat(element) {
    const status = element.dataset.status;
    const seatNumber = element.dataset.seatNumber;
    
    // Can't select booked seats
    if (status === 'booked') return;
    
    // If already selected, deselect
    if (element.classList.contains('selected')) {
        element.classList.remove('selected');
        selectedSeats = selectedSeats.filter(s => s !== seatNumber);
        updateUI();
        return;
    }
    
    // Check max selection
    if (selectedSeats.length >= maxSeats) {
        alert('Kamu hanya bisa memilih ' + maxSeats + ' kursi.');
        return;
    }
    
    // Select
    element.classList.add('selected');
    selectedSeats.push(seatNumber);
    updateUI();
}

function updateUI() {
    const info = document.getElementById('selectedSeatsInfo');
    const btn = document.getElementById('confirmSeatsBtn');
    const container = document.getElementById('selectedSeatsContainer');
    
    if (selectedSeats.length === 0) {
        info.textContent = 'Belum ada kursi dipilih';
        btn.disabled = true;
        btn.textContent = 'Pilih Kursi Terlebih Dahulu';
        container.innerHTML = '';
        return;
    }
    
    info.textContent = selectedSeats.length + ' kursi dipilih: ' + selectedSeats.join(', ');
    
    if (selectedSeats.length === maxSeats) {
        btn.disabled = false;
        btn.textContent = 'Konfirmasi ' + selectedSeats.length + ' Kursi & Lanjutkan';
    } else {
        btn.disabled = true;
        btn.textContent = 'Pilih ' + (maxSeats - selectedSeats.length) + ' kursi lagi';
    }
    
    // Add hidden inputs for selected seats
    container.innerHTML = '';
    selectedSeats.forEach((seat, index) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'seat_numbers[' + index + ']';
        input.value = seat;
        container.appendChild(input);
    });
}

// Handle form submit validation
document.getElementById('seatForm')?.addEventListener('submit', function(e) {
    if (selectedSeats.length !== maxSeats) {
        e.preventDefault();
        alert('Silakan pilih ' + maxSeats + ' kursi terlebih dahulu.');
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\artaa\Downloads\maskapai-fixed-audit\maskapai-main\resources\views/customer/seat-selection.blade.php ENDPATH**/ ?>