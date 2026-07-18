<?php if (isset($component)) { $__componentOriginal1e3104080c84f48e29bb256e3b9852ae = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1e3104080c84f48e29bb256e3b9852ae = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.staff-layout','data' => ['title' => 'Bookings - drgMaskapai','header' => 'Manage Bookings']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('staff-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Bookings - drgMaskapai','header' => 'Manage Bookings']); ?>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Booking Code</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Flight</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Passengers</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-slate-900"><?php echo e($booking->booking_code); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-slate-900"><?php echo e($booking->user->name); ?></p>
                            <p class="text-xs text-slate-500"><?php echo e($booking->user->email); ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-slate-900"><?php echo e($booking->flight->flight_number); ?></p>
                            <p class="text-xs text-slate-500"><?php echo e($booking->flight->departureAirport->iata_code); ?> → <?php echo e($booking->flight->arrivalAirport->iata_code); ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-slate-600"><?php echo e($booking->total_passengers); ?></span>
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
                                $statusColors = [
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'confirmed' => 'bg-green-100 text-green-700',
                                    'in_progress' => 'bg-blue-100 text-blue-700',
                                    'completed' => 'bg-emerald-100 text-emerald-700',
                                    'cancelled' => 'bg-red-100 text-red-700'
                                ];
                                $statusLabels = [
                                    'pending' => 'Pending Payment',
                                    'confirmed' => 'Confirmed',
                                    'in_progress' => 'In Progress',
                                    'completed' => 'Completed',
                                    'cancelled' => 'Cancelled'
                                ];
                                $color = $statusColors[$booking->status] ?? 'bg-slate-100 text-slate-700';
                                $label = $statusLabels[$booking->status] ?? ucfirst($booking->status);
                            ?>
                            <span class="px-3 py-1 text-xs font-bold rounded-full <?php echo e($color); ?>"><?php echo e($label); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="<?php echo e(route('staff.booking.show', $booking)); ?>" class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-lg transition">View</a>
                                <?php if($booking->status === 'pending'): ?>
                                    <form action="<?php echo e(route('staff.booking.updateStatus', $booking)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition" onclick="return confirm('Approve booking?')">Approve</button>
                                    </form>
                                    <form action="<?php echo e(route('staff.booking.updateStatus', $booking)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-lg transition" onclick="return confirm('Cancel booking?')">Cancel</button>
                                    </form>
                                <?php endif; ?>
                                <?php if($booking->payment && $booking->payment->payment_status === 'pending'): ?>
                                    <form action="<?php echo e(route('staff.booking.verifyPayment', $booking)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition" onclick="return confirm('Verify payment?')">Verify</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="8" class="px-6 py-8 text-center text-slate-500 text-sm">Belum ada booking</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($bookings->hasPages()): ?>
        <div class="px-6 py-4 border-t border-slate-100">
            <?php echo e($bookings->links()); ?>

        </div>
        <?php endif; ?>
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
<?php endif; ?><?php /**PATH C:\Users\artaa\Downloads\maskapai-fixed-audit\maskapai-main\resources\views/staff/bookings.blade.php ENDPATH**/ ?>