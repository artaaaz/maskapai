<?php if (isset($component)) { $__componentOriginal1e3104080c84f48e29bb256e3b9852ae = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1e3104080c84f48e29bb256e3b9852ae = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.staff-layout','data' => ['title' => 'Detail Penumpang - drgMaskapai','header' => 'Detail Penumpang']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('staff-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Detail Penumpang - drgMaskapai','header' => 'Detail Penumpang']); ?>
     <?php $__env->slot('headerRight', null, []); ?> 
        <a href="<?php echo e(route('staff.passengers')); ?>" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-sm transition">&larr; Kembali</a>
     <?php $__env->endSlot(); ?>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800"><?php echo e($passenger->full_name_with_title); ?></h2>
                    <p class="text-slate-500"><?php echo e($passenger->passport_number); ?></p>
                </div>
                <span class="px-3 py-1.5 rounded-lg text-sm font-medium <?php echo e($passenger->check_in_status['class']); ?>">
                    <?php echo e($passenger->check_in_status['label']); ?>

                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Data Penumpang</h3>
                    <table class="w-full">
                        <tr><td class="py-2 text-slate-500">Nama</td><td class="py-2 font-medium"><?php echo e($passenger->full_name_with_title); ?></td></tr>
                        <tr><td class="py-2 text-slate-500">Gender</td><td class="py-2 font-medium"><?php echo e($passenger->gender == 'male' ? 'Laki-laki' : 'Perempuan'); ?></td></tr>
                        <tr><td class="py-2 text-slate-500">Tanggal Lahir</td><td class="py-2 font-medium"><?php echo e($passenger->birth_date ? $passenger->birth_date->format('d/m/Y') : '-'); ?></td></tr>
                        <tr><td class="py-2 text-slate-500">Paspor</td><td class="py-2 font-medium"><?php echo e($passenger->passport_number); ?></td></tr>
                        <tr><td class="py-2 text-slate-500">Kursi</td><td class="py-2 font-medium"><?php echo e($passenger->seat_number ?? '-'); ?></td></tr>
                        <tr><td class="py-2 text-slate-500">Kelas</td><td class="py-2 font-medium"><?php echo e(ucfirst($passenger->travel_class ?? 'economy')); ?></td></tr>
                    </table>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Data Penerbangan</h3>
                    <table class="w-full">
                        <tr><td class="py-2 text-slate-500">Booking</td><td class="py-2 font-medium"><?php echo e($passenger->booking->booking_code ?? 'N/A'); ?></td></tr>
                        <tr><td class="py-2 text-slate-500">Penerbangan</td><td class="py-2 font-medium"><?php echo e($passenger->booking->flight->flight_number ?? 'N/A'); ?></td></tr>
                        <tr><td class="py-2 text-slate-500">Maskapai</td><td class="py-2 font-medium"><?php echo e($passenger->booking->flight->airline->name ?? 'N/A'); ?></td></tr>
                        <tr><td class="py-2 text-slate-500">Rute</td><td class="py-2 font-medium"><?php echo e($passenger->booking->flight->departureAirport->city ?? '??'); ?> → <?php echo e($passenger->booking->flight->arrivalAirport->city ?? '??'); ?></td></tr>
                        <tr><td class="py-2 text-slate-500">Berangkat</td><td class="py-2 font-medium"><?php echo e($passenger->booking->flight->departure_time ? $passenger->booking->flight->departure_time->format('d/m/Y H:i') : '-'); ?></td></tr>
                        <tr><td class="py-2 text-slate-500">Tiba</td><td class="py-2 font-medium"><?php echo e($passenger->booking->flight->arrival_time ? $passenger->booking->flight->arrival_time->format('d/m/Y H:i') : '-'); ?></td></tr>
                    </table>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Status Perjalanan</h3>
                <div class="grid grid-cols-4 gap-4">
                    <div class="p-4 rounded-xl <?php echo e($passenger->has_checked_in ? 'bg-green-50 border border-green-200' : 'bg-slate-50'); ?>">
                        <p class="text-sm font-semibold <?php echo e($passenger->has_checked_in ? 'text-green-700' : 'text-slate-400'); ?>">Check-in</p>
                        <p class="text-xs <?php echo e($passenger->checked_in_at ? '' : 'text-slate-400'); ?>"><?php echo e($passenger->checked_in_at ? $passenger->checked_in_at->format('H:i d/m/Y') : 'Belum'); ?></p>
                    </div>
                    <div class="p-4 rounded-xl <?php echo e($passenger->has_boarded ? 'bg-green-50 border border-green-200' : 'bg-slate-50'); ?>">
                        <p class="text-sm font-semibold <?php echo e($passenger->has_boarded ? 'text-green-700' : 'text-slate-400'); ?>">Boarding</p>
                        <p class="text-xs <?php echo e($passenger->boarded_at ? '' : 'text-slate-400'); ?>"><?php echo e($passenger->boarded_at ? $passenger->boarded_at->format('H:i d/m/Y') : 'Belum'); ?></p>
                    </div>
                    <div class="p-4 rounded-xl <?php echo e($passenger->checked_out_at ? 'bg-green-50 border border-green-200' : 'bg-slate-50'); ?>">
                        <p class="text-sm font-semibold <?php echo e($passenger->checked_out_at ? 'text-green-700' : 'text-slate-400'); ?>">Check-out</p>
                        <p class="text-xs <?php echo e($passenger->checked_out_at ? '' : 'text-slate-400'); ?>"><?php echo e($passenger->checked_out_at ? $passenger->checked_out_at->format('H:i d/m/Y') : 'Belum'); ?></p>
                    </div>
                    <div class="p-4 rounded-xl <?php echo e($passenger->booking->status == 'confirmed' ? 'bg-green-50 border border-green-200' : 'bg-slate-50'); ?>">
                        <p class="text-sm font-semibold <?php echo e($passenger->booking->status == 'confirmed' ? 'text-green-700' : 'text-slate-400'); ?>">Status</p>
                        <p class="text-xs"><?php echo e(ucfirst($passenger->booking->status ?? 'pending')); ?></p>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <?php if(!$passenger->has_checked_in): ?>
                    <form method="POST" action="<?php echo e(route('staff.passenger.checkin', $passenger)); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl">Check-in Penumpang</button>
                    </form>
                <?php endif; ?>
                <?php if($passenger->has_checked_in && !$passenger->has_boarded): ?>
                    <form method="POST" action="<?php echo e(route('staff.passenger.board', $passenger)); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl">Boarding Penumpang</button>
                    </form>
                <?php endif; ?>
                <?php if($passenger->has_boarded && !$passenger->checked_out_at): ?>
                    <form method="POST" action="<?php echo e(route('staff.passenger.checkout', $passenger)); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="px-6 py-2.5 bg-slate-600 hover:bg-slate-700 text-white font-semibold rounded-xl">Check-out Penumpang</button>
                    </form>
                <?php endif; ?>
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
<?php endif; ?><?php /**PATH C:\Users\artaa\Downloads\maskapai-fixed-audit\maskapai-main\resources\views/staff/passenger-detail.blade.php ENDPATH**/ ?>