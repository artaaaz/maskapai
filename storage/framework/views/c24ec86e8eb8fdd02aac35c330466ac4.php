<?php $__env->startSection('content'); ?>
<?php
    // booking.status memiliki 8 kemungkinan nilai valid:
    // pending, paid, confirmed, checked_in, boarded, in_progress, completed, cancelled.
    // Hanya 'pending' dan 'cancelled' yang BUKAN status sukses.
    // paid/confirmed/checked_in/boarded/in_progress/completed SEMUA berarti
    // pembayaran berhasil dan booking valid.
    $isCancelled = $booking->status === 'cancelled';
    $isPending = $booking->status === 'pending';
    $isSuccess = !$isCancelled && !$isPending;
?>
<div class="bg-slate-100 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        
        <?php if(session('success')): ?>
        <div class="bg-green-50 border-2 border-green-400 text-green-700 px-6 py-4 rounded-xl mb-6 flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="font-bold"><?php echo e(session('success')); ?></p>
        </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <div class="bg-red-50 border-2 border-red-400 text-red-700 px-6 py-4 rounded-xl mb-6 flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="font-bold"><?php echo e(session('error')); ?></p>
        </div>
        <?php endif; ?>

        <?php if(session('info')): ?>
        <div class="bg-blue-50 border-2 border-blue-400 text-blue-700 px-6 py-4 rounded-xl mb-6 flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="font-bold"><?php echo e(session('info')); ?></p>
        </div>
        <?php endif; ?>

        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Detail Booking</h2>
                <p class="text-slate-500">Kode: <span class="font-bold"><?php echo e($booking->booking_code); ?></span></p>
            </div>
            <div class="flex gap-3">
                <?php if($isSuccess): ?>
                    <a href="<?php echo e(route('customer.booking.e-ticket', $booking)); ?>" 
                       class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors">
                        📱 Lihat E-Ticket
                    </a>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl
                        <?php if($isSuccess): ?> bg-green-100
                        <?php elseif($isPending): ?> bg-amber-100
                        <?php else: ?> bg-red-100 <?php endif; ?>">
                        <?php if($isSuccess): ?> ✅
                        <?php elseif($isPending): ?> ⏳
                        <?php else: ?> ❌ <?php endif; ?>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-slate-800">
                            <?php if($isSuccess): ?> Pembayaran Berhasil! Tiket Aktif
                            <?php elseif($isPending): ?> Menunggu Pembayaran
                            <?php else: ?> Pembayaran Gagal / Dibatalkan <?php endif; ?>
                        </p>
                        <p class="text-sm text-slate-500">
                            <?php if($isSuccess): ?>
                                <?php echo e($booking->status_badge['label']); ?> &mdash; E-ticket tersedia, silakan lihat atau cetak tiket Anda.
                            <?php elseif($isPending): ?>
                                Silakan selesaikan pembayaran dalam 24 jam.
                            <?php else: ?>
                                Booking telah dibatalkan. Silakan hubungi staff untuk informasi lebih lanjut.
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                <span class="px-4 py-2 rounded-xl text-sm font-bold 
                    <?php if($isSuccess): ?> bg-green-100 text-green-700
                    <?php elseif($isPending): ?> bg-amber-100 text-amber-700
                    <?php else: ?> bg-red-100 text-red-700 <?php endif; ?>">
                    <?php echo e(strtoupper($booking->status_badge['label'])); ?>

                </span>
            </div>
        </div>

        
        <?php if($isPending && $booking->payment): ?>
        <div class="bg-amber-50 border-2 border-amber-300 rounded-2xl p-6 mb-6">
            <div class="flex items-start gap-4">
                <span class="text-2xl">⚠️</span>
                <div class="flex-1">
                    <h3 class="font-bold text-amber-900 mb-2">Pembayaran Belum Selesai</h3>
                    <p class="text-amber-700 text-sm mb-4">
                        Status pembayaran Anda masih <strong><?php echo e($booking->payment->payment_status); ?></strong>. 
                        Silakan lakukan pembayaran melalui tombol di bawah.
                    </p>
                    <div class="flex gap-3">
                        <a href="<?php echo e(route('customer.payment.show', $booking)); ?>" 
                           class="px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl transition-colors">
                            💳 Lanjut Bayar
                        </a>
                        <form method="POST" action="<?php echo e(route('customer.booking.show', $booking)); ?>" id="checkPaymentForm">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('GET'); ?>
                            <input type="hidden" name="payment" value="1">
                            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors">
                                🔄 Cek Status Pembayaran
                            </button>
                        </form>
                        <?php if(config('app.env') === 'local'): ?>
                        <form method="POST" action="<?php echo e(route('customer.payment.test-success', $booking)); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-colors">
                                ✅ Simulasi Bayar Berhasil (Test)
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        
        <?php if($isSuccess): ?>
        <div class="bg-green-50 border-2 border-green-300 rounded-2xl p-6 mb-6">
            <div class="flex items-start gap-4">
                <span class="text-2xl">🎉</span>
                <div class="flex-1">
                    <h3 class="font-bold text-green-900 mb-2">Pembayaran Berhasil!</h3>
                    <p class="text-green-700 text-sm mb-4">
                        Tiket Anda sudah aktif. Silakan lihat e-ticket untuk informasi lengkap penerbangan.
                    </p>
                    <div class="flex gap-3">
                        <a href="<?php echo e(route('customer.booking.e-ticket', $booking)); ?>" 
                           class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-colors">
                            📱 Lihat E-Ticket
                        </a>
                        <a href="<?php echo e(route('customer.booking.e-ticket', $booking)); ?>" onclick="window.open(this.href,'_blank');return false;"
                           class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors">
                            🖨️ Cetak E-Ticket
                        </a>
                    </div>
                </div>
            </div>
        </div>

        
        <?php if($booking->seats->count() === 0): ?>
        <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6 mb-6">
            <div class="flex items-start gap-4">
                <span class="text-2xl">💺</span>
                <div class="flex-1">
                    <h3 class="font-bold text-blue-900 mb-2">Pilih Kursi Penerbangan</h3>
                    <p class="text-sm text-blue-700 mb-4">Silakan pilih kursi yang Anda inginkan untuk penerbangan ini</p>
                    <a href="<?php echo e(route('customer.booking.select-seat', $booking)); ?>" 
                       class="inline-block px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl">
                        Pilih Kursi Sekarang
                    </a>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="bg-green-50 border-2 border-green-200 rounded-xl p-6 mb-6">
            <div class="flex items-start gap-4">
                <span class="text-2xl">💺</span>
                <div class="flex-1">
                    <h3 class="font-bold text-green-900 mb-2">Kursi Anda</h3>
                    <p class="text-sm text-green-700 mb-3">Kursi yang telah Anda pilih:</p>
                    <div class="flex flex-wrap gap-2">
                        <?php $__currentLoopData = $booking->seats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="px-4 py-2 bg-white border-2 border-green-300 text-green-700 font-bold rounded-lg">
                                <?php echo e($seat->seat_number); ?>

                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>

        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">Informasi Penerbangan</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="text-center">
                        <p class="text-3xl font-black text-slate-800"><?php echo e($booking->flight->departureAirport->iata_code); ?></p>
                        <p class="text-xs text-slate-500"><?php echo e($booking->flight->departureAirport->city); ?></p>
                        <p class="text-lg font-bold text-slate-700 mt-2"><?php echo e($booking->flight->departure_time->format('H:i')); ?></p>
                        <p class="text-xs text-slate-400"><?php echo e($booking->flight->departure_time->format('d M Y')); ?></p>
                    </div>
                    <div class="flex-1 mx-6">
                        <div class="border-t-2 border-dashed border-slate-300 relative">
                            <div class="absolute -top-2 -left-1 w-4 h-4 bg-slate-100 rounded-full"></div>
                            <div class="absolute -top-2 -right-1 w-4 h-4 bg-slate-100 rounded-full"></div>
                        </div>
                        <p class="text-center text-xs text-slate-500 mt-2">
                            <?php echo e($booking->flight->duration_formatted); ?> • <?php echo e($booking->flight->flight_number); ?>

                        </p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-black text-slate-800"><?php echo e($booking->flight->arrivalAirport->iata_code); ?></p>
                        <p class="text-xs text-slate-500"><?php echo e($booking->flight->arrivalAirport->city); ?></p>
                        <p class="text-lg font-bold text-slate-700 mt-2"><?php echo e($booking->flight->arrival_time->format('H:i')); ?></p>
                        <p class="text-xs text-slate-400"><?php echo e($booking->flight->arrival_time->format('d M Y')); ?></p>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-xl p-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center font-bold text-blue-600">
                            <?php echo e(substr($booking->flight->airline->name ?? 'DG', 0, 2)); ?>

                        </div>
                        <div>
                            <p class="font-bold text-slate-800"><?php echo e($booking->flight->airline->name ?? 'drgMaskapai'); ?></p>
                            <p class="text-xs text-slate-500"><?php echo e($booking->flight->airplane->model ?? ''); ?> • <?php echo e($booking->travel_class ?? 'Economy'); ?></p>
                        </div>
                        <div class="ml-auto text-right">
                            <p class="text-sm font-semibold text-slate-700"><?php echo e($booking->trip_type == 'round_trip' ? 'Round Trip' : 'One Way'); ?></p>
                            <p class="text-xs text-slate-400"><?php echo e($booking->total_passengers); ?> penumpang</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <?php if($booking->passengers->count() > 0): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">Data Penumpang (<?php echo e($booking->passengers->count()); ?>)</h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase">Penumpang</th>
                                <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase">Kursi</th>
                                <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php $__currentLoopData = $booking->passengers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $passenger): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center font-bold text-blue-600">
                                            <?php echo e(substr($passenger->full_name, 0, 1)); ?>

                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800"><?php echo e($passenger->full_name_with_title); ?></p>
                                            <p class="text-xs text-slate-400"><?php echo e($passenger->passport_number); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-bold text-slate-700"><?php echo e($passenger->seat_number ?? '-'); ?></span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-lg text-xs font-medium <?php echo e($passenger->check_in_status['class']); ?>"><?php echo e($passenger->check_in_status['label']); ?></span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        
        <?php if($booking->payment): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">Informasi Pembayaran</h3>
            </div>
            <div class="p-6">
                <table class="w-full text-sm">
                    <tr><td class="py-2 text-slate-500">Total Harga Tiket</td><td class="py-2 font-bold text-right">Rp <?php echo e(number_format($booking->total_price, 0, ',', '.')); ?></td></tr>
                    <?php if($booking->discount_amount > 0): ?>
                    <tr><td class="py-2 text-slate-500">Diskon</td><td class="py-2 font-bold text-right text-green-600">-Rp <?php echo e(number_format($booking->discount_amount, 0, ',', '.')); ?></td></tr>
                    <?php endif; ?>
                    <tr><td class="py-2 text-slate-500">Biaya Layanan</td><td class="py-2 font-bold text-right">Rp <?php echo e(number_format($booking->convenience_fee, 0, ',', '.')); ?></td></tr>
                    <tr class="border-t border-slate-200">
                        <td class="py-3 text-slate-700 font-bold">Total Dibayar</td>
                        <td class="py-3 font-black text-right text-blue-700">Rp <?php echo e(number_format($booking->final_price, 0, ',', '.')); ?></td>
                    </tr>
                    <tr><td class="py-2 text-slate-500">Metode Pembayaran</td><td class="py-2 font-bold text-right"><?php echo e($booking->payment->payment_method ?? '-'); ?></td></tr>
                    <tr><td class="py-2 text-slate-500">Status Pembayaran</td><td class="py-2 text-right">
                        <span class="px-3 py-1 rounded-lg text-sm font-bold 
                            <?php if($booking->payment->payment_status == 'paid'): ?> bg-green-100 text-green-700
                            <?php elseif($booking->payment->payment_status == 'pending'): ?> bg-amber-100 text-amber-700
                            <?php else: ?> bg-red-100 text-red-700 <?php endif; ?>">
                            <?php echo e($booking->payment->payment_status == 'paid' ? 'LUNAS' : ($booking->payment->payment_status == 'pending' ? 'BELUM DIBAYAR' : 'GAGAL')); ?>

                        </span>
                    </td></tr>
                    <?php if($booking->payment->paid_at): ?>
                    <tr><td class="py-2 text-slate-500">Waktu Pembayaran</td><td class="py-2 font-bold text-right"><?php echo e($booking->payment->paid_at->format('d M Y H:i')); ?></td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <?php if($isCancelled): ?>
        <div class="bg-red-50 border-2 border-red-200 rounded-2xl p-6">
            <p class="text-red-700 text-center font-bold">Booking ini telah dibatalkan. Silakan hubungi customer service untuk informasi lebih lanjut.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Auto-check payment status on page load if pending
<?php if($isPending && $booking->payment): ?>
document.addEventListener('DOMContentLoaded', function() {
    // Check if URL has ?payment=1
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('payment') === '1') {
        checkPaymentStatus();
    }
});

function checkPaymentStatus() {
    fetch('/customer/payment/<?php echo e($booking->id); ?>/verify', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Content-Type': 'application/json',
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        } else if (data.status === 'pending') {
            // Refresh page after 5 seconds
            setTimeout(() => location.reload(), 5000);
        }
    })
    .catch(() => {});
}
<?php endif; ?>
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\artaa\Downloads\maskapai-fixed-audit\maskapai-main\resources\views/customer/booking-show.blade.php ENDPATH**/ ?>