<?php if (isset($component)) { $__componentOriginal1e3104080c84f48e29bb256e3b9852ae = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1e3104080c84f48e29bb256e3b9852ae = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.staff-layout','data' => ['title' => 'Laporan - drgMaskapai','header' => 'Laporan']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('staff-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Laporan - drgMaskapai','header' => 'Laporan']); ?>
     <?php $__env->slot('headerRight', null, []); ?> 
        <div class="flex items-center gap-2">
            
            <form id="exportForm" action="<?php echo e(route('staff.reports.export.csv')); ?>" method="GET" class="flex items-center gap-2">
                <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" class="px-2 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Dari">
                <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" class="px-2 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Sampai">
                <select name="status" class="px-2 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Semua Status</option>
                    <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                    <option value="confirmed" <?php echo e(request('status') == 'confirmed' ? 'selected' : ''); ?>>Confirmed</option>
                    <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                </select>
                <div class="relative group">
                    <button type="button" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-sm transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Export
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div class="absolute right-0 top-full mt-1 bg-white rounded-xl shadow-xl border border-slate-200 py-2 min-w-[180px] hidden group-hover:block z-50">
                        <button type="submit" form="exportForm" class="w-full px-4 py-2 text-left text-sm text-slate-700 hover:bg-slate-50 flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Export Excel (.csv)
                        </button>
                        <a href="<?php echo e(route('staff.reports.print', request()->query())); ?>" target="_blank" class="w-full px-4 py-2 text-left text-sm text-slate-700 hover:bg-slate-50 flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            Export PDF / Print
                        </a>
                    </div>
                </div>
            </form>
        </div>
     <?php $__env->endSlot(); ?>

    <?php
        $totalBookings = \App\Models\Booking::count();
        $totalPassengers = \App\Models\Passenger::count();
        $totalRevenue = \App\Models\Payment::where('payment_status', 'paid')->sum('amount');
        $pendingPayments = \App\Models\Payment::where('payment_status', 'pending')->count();
        $confirmedBookings = \App\Models\Booking::where('status', 'confirmed')->count();
        $today = \Carbon\Carbon::today();
        $boardingToday = \App\Models\Passenger::where('has_boarded', true)
            ->whereHas('booking.flight', function($q) use ($today) {
                $q->whereDate('departure_time', $today);
            })->count();
    ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <p class="text-slate-400 text-xs font-bold uppercase mb-2">Total Booking</p>
            <p class="text-3xl font-bold text-slate-800"><?php echo e($totalBookings); ?></p>
            <p class="text-green-600 text-xs font-semibold mt-2"><?php echo e($confirmedBookings); ?> confirmed</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <p class="text-slate-400 text-xs font-bold uppercase mb-2">Total Penumpang</p>
            <p class="text-3xl font-bold text-slate-800"><?php echo e($totalPassengers); ?></p>
            <p class="text-blue-600 text-xs font-semibold mt-2">Semua waktu</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <p class="text-slate-400 text-xs font-bold uppercase mb-2">Total Pendapatan</p>
            <p class="text-3xl font-bold text-green-600">Rp<?php echo e(number_format($totalRevenue, 0, ',', '.')); ?></p>
            <p class="text-green-600 text-xs font-semibold mt-2">Dari pembayaran terverifikasi</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <p class="text-slate-400 text-xs font-bold uppercase mb-2">Pembayaran Pending</p>
            <p class="text-3xl font-bold text-amber-600"><?php echo e($pendingPayments); ?></p>
            <p class="text-amber-600 text-xs font-semibold mt-2">Menunggu verifikasi</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <p class="text-slate-400 text-xs font-bold uppercase mb-2">Confirmed Booking</p>
            <p class="text-3xl font-bold text-emerald-600"><?php echo e($confirmedBookings); ?></p>
            <p class="text-emerald-600 text-xs font-semibold mt-2">Terverifikasi</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <p class="text-slate-400 text-xs font-bold uppercase mb-2">Boarding Hari Ini</p>
            <p class="text-3xl font-bold text-purple-600"><?php echo e($boardingToday); ?></p>
            <p class="text-purple-600 text-xs font-semibold mt-2">Penumpang sudah boarding</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-800">Booking Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Flight</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php
                        $recentBookings = \App\Models\Booking::with(['user', 'flight.airline', 'payment'])->latest()->limit(10)->get();
                    ?>
                    <?php $__empty_1 = true; $__currentLoopData = $recentBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm font-bold text-slate-900"><?php echo e($booking->booking_code); ?></td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-slate-900"><?php echo e($booking->user->name ?? 'N/A'); ?></p>
                            <p class="text-xs text-slate-500"><?php echo e($booking->user->email ?? ''); ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-slate-900"><?php echo e($booking->flight->flight_number ?? 'N/A'); ?></p>
                            <p class="text-xs text-slate-500"><?php echo e($booking->flight->airline->name ?? ''); ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-slate-900">Rp<?php echo e(number_format($booking->total_price, 0, ',', '.')); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($booking->payment): ?>
                                <?php
                                    $payColors = ['paid' => 'bg-green-100 text-green-700', 'pending' => 'bg-amber-100 text-amber-700', 'failed' => 'bg-red-100 text-red-700'];
                                    $payColor = $payColors[$booking->payment->payment_status] ?? 'bg-slate-100 text-slate-600';
                                ?>
                                <span class="px-2 py-1 text-xs font-bold rounded-full <?php echo e($payColor); ?>"><?php echo e(ucfirst($booking->payment->payment_status)); ?></span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-600">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php
                                $statusColors = ['confirmed' => 'bg-green-100 text-green-700', 'pending' => 'bg-amber-100 text-amber-700', 'cancelled' => 'bg-red-100 text-red-700'];
                                $color = $statusColors[$booking->status] ?? 'bg-slate-100 text-slate-700';
                            ?>
                            <span class="px-3 py-1 text-xs font-bold rounded-full <?php echo e($color); ?>"><?php echo e(ucfirst($booking->status)); ?></span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400 text-sm">Belum ada data booking</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
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
<?php endif; ?><?php /**PATH C:\Users\artaa\Downloads\maskapai-fixed-audit\maskapai-main\resources\views/staff/reports.blade.php ENDPATH**/ ?>