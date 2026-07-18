<?php if (isset($component)) { $__componentOriginale0f1cdd055772eb1d4a99981c240763e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale0f1cdd055772eb1d4a99981c240763e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> Kelas Penerbangan <?php $__env->endSlot(); ?>
     <?php $__env->slot('breadcrumb', null, []); ?> 
        <a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
        <span class="separator">/</span>
        <a href="<?php echo e(route('admin.flights.index')); ?>">Jadwal Penerbangan</a>
        <span class="separator">/</span>
        <span>Flight Classes</span>
     <?php $__env->endSlot(); ?>

    <div class="card">
        <div class="card-header">
            <h2>Kelas Penerbangan: <?php echo e($flight->flight_number); ?> (<?php echo e($flight->route); ?>)</h2>
            <a href="<?php echo e(route('admin.flights.flight-classes.create', $flight)); ?>" class="btn btn-success">+ Tambah Kelas</a>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success mx-6 mt-6"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger mx-6 mt-6"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Kelas</th>
                        <th>Harga</th>
                        <th>Kuota</th>
                        <th>Tersedia</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $flight->flightClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><span class="font-bold capitalize"><?php echo e(str_replace('_', ' ', $fc->class_name)); ?></span></td>
                        <td><span class="font-bold text-slate-900">Rp <?php echo e(number_format($fc->price, 0, ',', '.')); ?></span></td>
                        <td><?php echo e($fc->seat_quota); ?></td>
                        <td>
                            <span class="badge <?php echo e($fc->available_quota > 0 ? 'badge-green' : 'badge-red'); ?>">
                                <?php echo e($fc->available_quota); ?>

                            </span>
                        </td>
                        <td class="text-right">
                            <div class="action-group justify-end">
                                <a href="<?php echo e(route('admin.flights.flight-classes.edit', [$flight, $fc])); ?>" class="btn btn-primary btn-sm">Edit</a>
                                <form action="<?php echo e(route('admin.flights.flight-classes.destroy', [$flight, $fc])); ?>" method="POST" class="inline" onsubmit="return confirm('Hapus kelas ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <h3>Belum Ada Kelas Penerbangan</h3>
                                <p>Tambahkan kelas untuk flight <?php echo e($flight->flight_number); ?>.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale0f1cdd055772eb1d4a99981c240763e)): ?>
<?php $attributes = $__attributesOriginale0f1cdd055772eb1d4a99981c240763e; ?>
<?php unset($__attributesOriginale0f1cdd055772eb1d4a99981c240763e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale0f1cdd055772eb1d4a99981c240763e)): ?>
<?php $component = $__componentOriginale0f1cdd055772eb1d4a99981c240763e; ?>
<?php unset($__componentOriginale0f1cdd055772eb1d4a99981c240763e); ?>
<?php endif; ?><?php /**PATH C:\Users\artaa\Downloads\maskapai-fixed-audit\maskapai-main\resources\views/admin/flight-classes/index.blade.php ENDPATH**/ ?>