<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - drgMaskapai</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>* { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-6">
    <div class="w-full max-w-md">
        <div class="text-center mb-6">
            <a href="<?php echo e(route('customer.login')); ?>" class="text-3xl font-black text-slate-800">drg<span class="text-yellow-500">.</span>Maskapai</a>
            <p class="text-slate-500 mt-2">Buat akun pelanggan baru</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl p-8">
            <?php if($errors->any()): ?>
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('customer.register.store')); ?>" id="registerForm" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" required autofocus
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500">
                </div>
                
                <?php if (isset($component)) { $__componentOriginal477f82871d526f2af6146ef21269b1c0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal477f82871d526f2af6146ef21269b1c0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.recaptcha-v2','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('recaptcha-v2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal477f82871d526f2af6146ef21269b1c0)): ?>
<?php $attributes = $__attributesOriginal477f82871d526f2af6146ef21269b1c0; ?>
<?php unset($__attributesOriginal477f82871d526f2af6146ef21269b1c0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal477f82871d526f2af6146ef21269b1c0)): ?>
<?php $component = $__componentOriginal477f82871d526f2af6146ef21269b1c0; ?>
<?php unset($__componentOriginal477f82871d526f2af6146ef21269b1c0); ?>
<?php endif; ?>

                <button type="submit"
                        class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg">
                    Daftar Sekarang
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-600">
                Sudah punya akun?
                <a href="<?php echo e(route('customer.login')); ?>" class="text-blue-600 font-bold hover:underline">Masuk</a>
            </p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\artaa\Downloads\maskapai-fixed-audit\maskapai-main\resources\views/auth/customer/register.blade.php ENDPATH**/ ?>