<?php if (isset($component)) { $__componentOriginal1e3104080c84f48e29bb256e3b9852ae = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1e3104080c84f48e29bb256e3b9852ae = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.staff-layout','data' => ['title' => 'Detail Booking - drgMaskapai','header' => 'Detail Booking']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('staff-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Detail Booking - drgMaskapai','header' => 'Detail Booking']); ?>
     <?php $__env->slot('headerRight', null, []); ?> 
        <a href="<?php echo e(route('staff.bookings')); ?>" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-sm transition">&larr; Kembali</a>
     <?php $__env->endSlot(); ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Informasi Booking</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase">Kode Booking</p>
                        <p class="text-sm font-bold text-slate-800"><?php echo e($booking->booking_code); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase">Status</p>
                        <span class="px-3 py-1 text-xs font-bold rounded-full <?php echo e($booking->status_badge['class']); ?>"><?php echo e($booking->status_badge['label']); ?></span>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase">Tanggal Booking</p>
                        <p class="text-sm font-bold text-slate-800"><?php echo e($booking->created_at->format('d/m/Y H:i')); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase">Total Penumpang</p>
                        <p class="text-sm font-bold text-slate-800"><?php echo e($booking->total_passengers); ?> orang</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase">Tipe Perjalanan</p>
                        <p class="text-sm font-bold text-slate-800"><?php echo e($booking->trip_type === 'round_trip' ? 'Pulang Pergi' : 'Sekali Jalan'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase">Kelas</p>
                        <p class="text-sm font-bold text-slate-800 capitalize"><?php echo e(str_replace('_', ' ', $booking->travel_class ?? 'economy')); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Data Pemesan</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase">Nama</p>
                        <p class="text-sm font-bold text-slate-800"><?php echo e($booking->user->name ?? 'N/A'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase">Email</p>
                        <p class="text-sm font-bold text-slate-800"><?php echo e($booking->user->email ?? 'N/A'); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Data Penerbangan</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-xl">
                        <?php if($booking->flight->airline->logo): ?>
                            <img src="<?php echo e(asset('storage/' . $booking->flight->airline->logo)); ?>" class="w-10 h-10 rounded-lg object-cover">
                        <?php else: ?>
                            <div class="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center text-white font-bold text-sm"><?php echo e(substr($booking->flight->airline->name ?? 'DRG', 0, 2)); ?></div>
                        <?php endif; ?>
                        <div>
                            <p class="font-bold text-slate-800"><?php echo e($booking->flight->airline->name); ?> - <?php echo e($booking->flight->flight_number); ?></p>
                            <p class="text-sm text-slate-500"><?php echo e($booking->flight->departureAirport->city); ?> (<?php echo e($booking->flight->departureAirport->iata_code); ?>) → <?php echo e($booking->flight->arrivalAirport->city); ?> (<?php echo e($booking->flight->arrivalAirport->iata_code); ?>)</p>
                            <p class="text-xs text-slate-400"><?php echo e($booking->flight->departure_time->format('d M Y H:i')); ?> - <?php echo e($booking->flight->arrival_time->format('H:i')); ?></p>
                        </div>
                    </div>
                    <?php if($booking->returnFlight): ?>
                    <div class="flex items-center gap-4 p-4 bg-emerald-50 rounded-xl">
                        <div class="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">PP</div>
                        <div>
                            <p class="font-bold text-slate-800"><?php echo e($booking->returnFlight->airline->name); ?> - <?php echo e($booking->returnFlight->flight_number); ?></p>
                            <p class="text-sm text-slate-500"><?php echo e($booking->returnFlight->departureAirport->city); ?> (<?php echo e($booking->returnFlight->departureAirport->iata_code); ?>) → <?php echo e($booking->returnFlight->arrivalAirport->city); ?> (<?php echo e($booking->returnFlight->arrivalAirport->iata_code); ?>)</p>
                            <p class="text-xs text-slate-400"><?php echo e($booking->returnFlight->departure_time->format('d M Y H:i')); ?> - <?php echo e($booking->returnFlight->arrival_time->format('H:i')); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Data Penumpang</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase">Nama</th>
                                <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase">Kursi</th>
                                <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php $__currentLoopData = $booking->passengers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-slate-800"><?php echo e($p->full_name_with_title); ?></td>
                                <td class="px-4 py-3 text-sm text-slate-600"><?php echo e($p->seat_number ?? '-'); ?></td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-lg text-xs font-medium <?php echo e($p->check_in_status['class']); ?>"><?php echo e($p->check_in_status['label']); ?></span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Pembayaran</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-slate-500">Total Harga</span>
                        <span class="text-sm font-bold text-slate-800">Rp<?php echo e(number_format($booking->total_price, 0, ',', '.')); ?></span>
                    </div>
                    <?php if($booking->discount_amount > 0): ?>
                    <div class="flex justify-between">
                        <span class="text-sm text-slate-500">Diskon</span>
                        <span class="text-sm font-bold text-green-600">-Rp<?php echo e(number_format($booking->discount_amount, 0, ',', '.')); ?></span>
                    </div>
                    <?php endif; ?>
                    <hr class="border-slate-200">
                    <div class="flex justify-between">
                        <span class="text-sm font-bold text-slate-800">Total Dibayar</span>
                        <span class="text-lg font-black text-emerald-600">Rp<?php echo e(number_format(($booking->total_price - $booking->discount_amount), 0, ',', '.')); ?></span>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase mb-1">Status Pembayaran</p>
                        <?php if($booking->payment): ?>
                            <?php
                                $payStatusColors = ['paid' => 'bg-green-100 text-green-700', 'pending' => 'bg-amber-100 text-amber-700', 'failed' => 'bg-red-100 text-red-700'];
                                $payColor = $payStatusColors[$booking->payment->payment_status] ?? 'bg-slate-100 text-slate-700';
                            ?>
                            <span class="px-3 py-1 text-xs font-bold rounded-full <?php echo e($payColor); ?>"><?php echo e(ucfirst($booking->payment->payment_status)); ?></span>
                            <?php if($booking->payment->paid_at): ?>
                                <p class="text-xs text-slate-400 mt-1"><?php echo e($booking->payment->paid_at->format('d/m/Y H:i')); ?></p>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-600">Belum Dibayar</span>
                        <?php endif; ?>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase mb-1">Metode</p>
                        <p class="text-sm font-bold text-slate-800"><?php echo e($booking->payment->payment_method ?? '-'); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Aksi</h3>
                <div class="space-y-3">
                    <?php if($booking->status === 'pending'): ?>
                        <form action="<?php echo e(route('staff.booking.updateStatus', $booking)); ?>" method="POST">
                            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition text-sm" onclick="return confirm('Konfirmasi booking ini?')">✅ Konfirmasi Booking</button>
                        </form>
                        <form action="<?php echo e(route('staff.booking.updateStatus', $booking)); ?>" method="POST">
                            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="w-full py-2.5 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl transition text-sm" onclick="return confirm('Batalkan booking ini?')">❌ Batalkan Booking</button>
                        </form>
                    <?php endif; ?>
                    <?php if($booking->payment && $booking->payment->payment_status === 'pending'): ?>
                        <form action="<?php echo e(route('staff.booking.verifyPayment', $booking)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition text-sm" onclick="return confirm('Verifikasi pembayaran booking ini?')">💳 Verify Payment</button>
                        </form>
                    <?php endif; ?>
                    <?php if($booking->status === 'confirmed' && $booking->payment?->payment_status === 'paid'): ?>
                        <a href="<?php echo e(route('staff.bookings')); ?>" class="block w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition text-sm text-center">Kembali ke Daftar</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1e3104080c84f48e29bb256e3b9852ae)): ?>
<?php $attributes = $__attributesOriginal1e3104080c84f48e29bb256e3b9852ae; ?>
<?php unset($__attributesOriginal1e3104080c84f48e29bb256e3b9852ae); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1e3104080c84f48e29bb256e3b9852ae)): ?>
<?php $component = $__componentOriginal1e3104080c84f48e29bb256e3b9852ae; ?>
<?php unset($__componentOriginal1e3104080c84f48e29bb256e3b9852ae); ?>
<?php endif; ?><?php /**PATH C:\Users\artaa\Downloads\maskapai-fixed-audit\maskapai-main\resources\views/staff/booking-show.blade.php ENDPATH**/ ?>