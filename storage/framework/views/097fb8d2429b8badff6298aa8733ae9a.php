<?php if (isset($component)) { $__componentOriginal1e3104080c84f48e29bb256e3b9852ae = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1e3104080c84f48e29bb256e3b9852ae = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.staff-layout','data' => ['title' => 'Data Penumpang - drgMaskapai','header' => 'Data Penumpang']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('staff-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Data Penumpang - drgMaskapai','header' => 'Data Penumpang']); ?>
     <?php $__env->slot('headerRight', null, []); ?> 
        <span class="text-sm text-slate-500">Total <?php echo e($passengers->total()); ?> penumpang</span>
     <?php $__env->endSlot(); ?>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Nama</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Booking</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Penerbangan</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Kursi</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Status</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $passengers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $passenger): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b border-slate-50 hover:bg-slate-50">
                            <td class="p-4">
                                <p class="font-semibold text-slate-800"><?php echo e($passenger->full_name_with_title); ?></p>
                                <p class="text-xs text-slate-400"><?php echo e($passenger->passport_number); ?></p>
                            </td>
                            <td class="p-4">
                                <p class="text-sm font-medium text-slate-700"><?php echo e($passenger->booking->booking_code ?? 'N/A'); ?></p>
                                <p class="text-xs text-slate-400"><?php echo e($passenger->booking->created_at->format('d/m/Y') ?? ''); ?></p>
                            </td>
                            <td class="p-4">
                                <p class="text-sm text-slate-700"><?php echo e($passenger->booking->flight->flight_number ?? 'N/A'); ?></p>
                                <p class="text-xs text-slate-400"><?php echo e($passenger->booking->flight->departureAirport->iata_code ?? '??'); ?> → <?php echo e($passenger->booking->flight->arrivalAirport->iata_code ?? '??'); ?></p>
                            </td>
                            <td class="p-4">
                                <span class="text-sm font-medium text-slate-700"><?php echo e($passenger->seat_number ?? '-'); ?></span>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded-lg text-xs font-medium <?php echo e($passenger->check_in_status['class']); ?>"><?php echo e($passenger->check_in_status['label']); ?></span>
                            </td>
                            <td class="p-4">
                                <div class="flex gap-2">
                                    <a href="<?php echo e(route('staff.passenger.show', $passenger)); ?>" class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-medium hover:bg-blue-100">Detail</a>
                                    <?php if(!$passenger->has_checked_in): ?>
                                        <form method="POST" action="<?php echo e(route('staff.passenger.checkin', $passenger)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-medium hover:bg-emerald-100">Check-in</button>
                                        </form>
                                    <?php elseif(!$passenger->has_boarded): ?>
                                        <form method="POST" action="<?php echo e(route('staff.passenger.board', $passenger)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="px-3 py-1.5 bg-amber-50 text-amber-600 rounded-lg text-xs font-medium hover:bg-amber-100">Boarding</button>
                                        </form>
                                    <?php elseif(!$passenger->checked_out_at): ?>
                                        <form method="POST" action="<?php echo e(route('staff.passenger.checkout', $passenger)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="px-3 py-1.5 bg-slate-50 text-slate-600 rounded-lg text-xs font-medium hover:bg-slate-100">Check-out</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="px-3 py-1.5 bg-slate-50 text-slate-400 rounded-lg text-xs font-medium">Selesai</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="p-8 text-center text-slate-400">Belum ada data penumpang</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        <?php echo e($passengers->links()); ?>

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
<?php endif; ?><?php /**PATH C:\Users\artaa\Downloads\maskapai-fixed-audit\maskapai-main\resources\views/staff/passengers.blade.php ENDPATH**/ ?>