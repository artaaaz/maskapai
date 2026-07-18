<?php if (isset($component)) { $__componentOriginal1e3104080c84f48e29bb256e3b9852ae = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1e3104080c84f48e29bb256e3b9852ae = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.staff-layout','data' => ['title' => 'Monitoring Penumpang - drgMaskapai','header' => 'Monitoring Penumpang']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('staff-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Monitoring Penumpang - drgMaskapai','header' => 'Monitoring Penumpang']); ?>
     <?php $__env->slot('headerRight', null, []); ?> 
        <span class="text-sm text-slate-500"><?php echo e($passengers->total()); ?> penumpang</span>
     <?php $__env->endSlot(); ?>

    
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-6">
        <form method="GET" action="<?php echo e(route('staff.monitoring')); ?>" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Status</label>
                <select name="status" class="rounded-xl border-slate-200 text-sm">
                    <option value="">Semua Status</option>
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(request('status') === $s ? 'selected' : ''); ?>><?php echo e(ucfirst(str_replace('_', ' ', $s))); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Penerbangan</label>
                <select name="flight_id" class="rounded-xl border-slate-200 text-sm">
                    <option value="">Semua Penerbangan</option>
                    <?php $__currentLoopData = $flights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($f->id); ?>" <?php echo e(request('flight_id') == $f->id ? 'selected' : ''); ?>>
                            <?php echo e($f->flight_number); ?> - <?php echo e($f->airline->name ?? ''); ?> (<?php echo e($f->departure_time->format('d/m H:i')); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Hari Ini</label>
                <select name="today" class="rounded-xl border-slate-200 text-sm">
                    <option value="">Semua</option>
                    <option value="1" <?php echo e(request('today') ? 'selected' : ''); ?>>Hari Ini</option>
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm">Filter</button>
                <a href="<?php echo e(route('staff.monitoring')); ?>" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-sm ml-2">Reset</a>
            </div>
        </form>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Kode Booking</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Penumpang</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Penerbangan</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Maskapai</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Kursi</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Status</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Berangkat</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Tiba</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $passengers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $passenger): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b border-slate-50 hover:bg-slate-50">
                            <td class="p-4">
                                <a href="<?php echo e(route('staff.booking.show', $passenger->booking)); ?>" class="text-blue-600 font-medium text-sm hover:underline">
                                    <?php echo e($passenger->booking->booking_code ?? 'N/A'); ?>

                                </a>
                            </td>
                            <td class="p-4">
                                <p class="font-semibold text-slate-800"><?php echo e($passenger->full_name_with_title); ?></p>
                                <p class="text-xs text-slate-400"><?php echo e($passenger->passport_number); ?></p>
                            </td>
                            <td class="p-4">
                                <p class="text-sm font-medium text-slate-700"><?php echo e($passenger->booking->flight->flight_number ?? 'N/A'); ?></p>
                                <p class="text-xs text-slate-400"><?php echo e($passenger->booking->flight->departureAirport->iata_code ?? '??'); ?> → <?php echo e($passenger->booking->flight->arrivalAirport->iata_code ?? '??'); ?></p>
                            </td>
                            <td class="p-4">
                                <span class="text-sm text-slate-700"><?php echo e($passenger->booking->flight->airline->name ?? 'N/A'); ?></span>
                            </td>
                            <td class="p-4">
                                <span class="text-sm font-medium text-slate-700"><?php echo e($passenger->seat_number ?? '-'); ?></span>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded-lg text-xs font-medium <?php echo e($passenger->check_in_status['class']); ?>"><?php echo e($passenger->check_in_status['label']); ?></span>
                            </td>
                            <td class="p-4">
                                <p class="text-sm text-slate-700"><?php echo e($passenger->booking->flight->departure_time ? $passenger->booking->flight->departure_time->format('H:i') : '-'); ?></p>
                                <p class="text-xs text-slate-400"><?php echo e($passenger->booking->flight->departure_time ? $passenger->booking->flight->departure_time->format('d/m') : ''); ?></p>
                            </td>
                            <td class="p-4">
                                <p class="text-sm text-slate-700"><?php echo e($passenger->booking->flight->arrival_time ? $passenger->booking->flight->arrival_time->format('H:i') : '-'); ?></p>
                                <p class="text-xs text-slate-400"><?php echo e($passenger->booking->flight->arrival_time ? $passenger->booking->flight->arrival_time->format('d/m') : ''); ?></p>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="8" class="p-8 text-center text-slate-400">Tidak ada data penumpang</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        <?php echo e($passengers->withQueryString()->links()); ?>

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
<?php endif; ?><?php /**PATH C:\Users\artaa\Downloads\maskapai-fixed-audit\maskapai-main\resources\views/staff/monitoring.blade.php ENDPATH**/ ?>