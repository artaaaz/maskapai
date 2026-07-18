<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - drgMaskapai</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>* { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="min-h-screen bg-slate-100">
    <div class="min-h-screen flex flex-col lg:flex-row">
        
        <div class="lg:w-1/2 relative min-h-[280px] lg:min-h-screen">
            <img src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=1200&h=800&fit=crop"
                 class="absolute inset-0 w-full h-full object-cover" alt="Travel">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/80 to-blue-700/70"></div>
            <div class="relative z-10 p-10 lg:p-16 flex flex-col justify-center h-full text-white">
                <div class="flex items-center gap-2 mb-8">
                    <span class="text-3xl font-black">drg<span class="text-yellow-400">.</span>Maskapai</span>
                </div>
                <h1 class="text-4xl lg:text-5xl font-black mb-4 leading-tight">Hai kamu, mau ke mana?</h1>
                <p class="text-blue-100 text-lg">Satu aplikasi untuk kebutuhan liburanmu. Pesan tiket pesawat dengan mudah dan aman.</p>
            </div>
        </div>

        
        <div class="lg:w-1/2 flex items-center justify-center p-6 lg:p-12">
            <div class="w-full max-w-md">
                <div class="bg-white rounded-3xl shadow-xl p-8">
                    <h2 class="text-2xl font-black text-slate-800 mb-2">Masuk</h2>
                    <p class="text-slate-500 mb-6 text-sm">Portal pelanggan drgMaskapai</p>

                    <?php if(session('success')): ?>
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm flex items-start gap-2">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span><?php echo e(session('success')); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if(session('warning')): ?>
                        <div class="mb-4 p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-700 text-sm flex items-start gap-2">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                            <span><?php echo e(session('warning')); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if($errors->any()): ?>
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p><?php echo e($error); ?></p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('customer.login.store')); ?>" id="loginForm" class="space-y-4">
                        <?php echo csrf_field(); ?>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                            <input type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                            <input type="password" name="password" required
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <label class="flex items-center gap-2 text-sm text-slate-600">
                            <input type="checkbox" name="remember" class="rounded text-blue-600">
                            Ingat saya
                        </label>

                        <button type="submit"
                                class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors shadow-lg">
                            Masuk
                        </button>
                    </form>

                    <p class="mt-6 text-center text-sm text-slate-600">
                        Belum punya akun?
                        <a href="<?php echo e(route('customer.register')); ?>" class="text-blue-600 font-bold hover:underline">Daftar</a>
                    </p>
                </div>

                <p class="text-center text-xs text-slate-400 mt-6">
                    Staff? <a href="<?php echo e(route('staff.login')); ?>" class="underline">Login staff</a> ·
                    Manager? <a href="<?php echo e(route('manager.login')); ?>" class="underline">Login manager</a> ·
                    Admin? <a href="<?php echo e(route('admin.login')); ?>" class="underline">Login admin</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\artaa\Downloads\maskapai-fixed-audit\maskapai-main\resources\views/auth/customer/login.blade.php ENDPATH**/ ?>