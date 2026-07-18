<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($title ?? 'Staff - drgMaskapai'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'IBM Plex Sans', sans-serif; }
        .sidebar-active { background: rgba(255,255,255,0.12); }
    </style>
</head>
<body class="bg-slate-50">
    <div class="flex h-screen">
        
        <div class="w-64 bg-gradient-to-b from-teal-900 to-emerald-900 text-white flex flex-col flex-shrink-0">
            <div class="p-6 border-b border-white/10">
                <h1 class="text-xl font-bold">drg<span class="text-yellow-400">.</span>Maskapai</h1>
                <p class="text-emerald-200 text-xs mt-1">Staff Portal</p>
            </div>
            <div class="p-4 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center font-bold"><?php echo e(substr(auth()->user()->name, 0, 1)); ?></div>
                    <div>
                        <p class="font-semibold text-sm"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-emerald-200 text-xs">Staff</p>
                    </div>
                </div>
            </div>
            <nav class="flex-1 p-4 space-y-1">
                <?php
                    $currentRoute = request()->route()?->getName() ?? '';
                    $menuItems = [
                        ['route' => 'staff.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                        ['route' => 'staff.bookings', 'label' => 'Bookings', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                        ['route' => 'staff.passengers', 'label' => 'Penumpang', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                        ['route' => 'staff.monitoring', 'label' => 'Monitoring', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        ['route' => 'staff.reports', 'label' => 'Laporan', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ];
                ?>
                <?php $__currentLoopData = $menuItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $isActive = str_starts_with($currentRoute, $item['route']) || $currentRoute === $item['route'];
                    ?>
                    <a href="<?php echo e(route($item['route'])); ?>"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                              <?php echo e($isActive ? 'bg-white/10 text-white font-medium' : 'text-emerald-100 hover:bg-white/5 hover:text-white'); ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($item['icon']); ?>"/>
                        </svg>
                        <?php echo e($item['label']); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </nav>
            <div class="p-4 border-t border-white/10">
                <form method="POST" action="<?php echo e(route('staff.logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 rounded-xl text-emerald-200 hover:text-white w-full transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        
        <div class="flex-1 overflow-y-auto">
            <div class="p-8">
                
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800"><?php echo e($header ?? 'Dashboard'); ?></h2>
                        <p class="text-slate-500"><?php echo e(now()->format('l, d F Y')); ?></p>
                    </div>
                    <div class="flex items-center gap-3">
                        <?php echo e($headerRight ?? ''); ?>

                    </div>
                </div>

                
                <?php if(session('success')): ?>
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm"><?php echo e(session('success')); ?></div>
                <?php endif; ?>

                
                <?php echo e($slot); ?>

            </div>
        </div>
    </div>
</body>
</html><?php /**PATH C:\Users\artaa\Downloads\maskapai-fixed-audit\maskapai-main\resources\views/components/staff-layout.blade.php ENDPATH**/ ?>