

<?php $__env->startSection('content'); ?>
    <div class="min-h-screen flex items-center justify-center bg-slate-50 p-6">
        <div class="max-w-md w-full text-center bg-white rounded-2xl shadow p-8">
            <div class="w-20 h-20 mx-auto bg-blue-50 rounded-full flex items-center justify-center mb-4">
                <img src="/icons/airplane.svg" alt="offline" class="w-10 h-10" />
            </div>
            <h2 class="text-2xl font-semibold mb-2 text-slate-800">Anda sedang offline</h2>
            <p class="text-sm text-slate-500 mb-4">Sepertinya koneksi internet terputus. Silakan periksa jaringan Anda dan coba lagi.</p>
            <div class="flex gap-3 justify-center">
                <a href="javascript:location.reload()" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Coba lagi</a>
                <a href="/" class="px-4 py-2 border border-slate-200 rounded-lg">Kembali</a>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\artaa\Downloads\maskapai-fixed-audit\maskapai-main\resources\views/offline.blade.php ENDPATH**/ ?>