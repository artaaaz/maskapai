<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-8 mb-6">
        <div class="flex flex-col md:flex-row items-center gap-6">
            
            <div class="relative">
                <?php if(Auth::user()->avatar): ?>
                    <img src="<?php echo e(asset('storage/avatars/' . Auth::user()->avatar)); ?>" alt="" class="w-24 h-24 rounded-full object-cover border-4 border-blue-100">
                <?php else: ?>
                    <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center border-4 border-blue-100">
                        <span class="text-3xl font-black text-blue-600"><?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?></span>
                    </div>
                <?php endif; ?>
                <form id="avatarForm" action="<?php echo e(route('customer.profile.avatar')); ?>" method="POST" enctype="multipart/form-data" class="absolute -bottom-2 -right-2">
                    <?php echo csrf_field(); ?>
                    <label class="w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center cursor-pointer shadow-md transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <input type="file" name="avatar" accept="image/*" class="hidden" onchange="document.getElementById('avatarForm').submit()">
                    </label>
                </form>
            </div>
            <div class="text-center md:text-left flex-1">
                <h1 class="text-2xl font-black text-slate-800"><?php echo e(Auth::user()->name); ?></h1>
                <p class="text-slate-500"><?php echo e(Auth::user()->email); ?></p>
                <p class="text-xs text-slate-400 mt-1">Member sejak <?php echo e(Auth::user()->created_at->format('d M Y')); ?></p>
            </div>
            <div class="text-center md:text-right">
                <a href="<?php echo e(route('customer.bookings')); ?>" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors text-sm">
                    Booking Saya
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Perjalanan Mendatang
                </h2>
                
                <?php if(isset($upcomingBookings) && $upcomingBookings->count() > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $upcomingBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $departure = \Carbon\Carbon::parse($booking->flight->departure_time);
                                $now = now();
                                
                                    
                                    $flightStatus = $booking->flight_status; // ['text', 'class'] from Booking model
                                    $countdownText = $flightStatus['text'];
                                    $countdownClass = $flightStatus['class'];
                            ?>
                            <div class="border border-slate-100 rounded-xl p-4 hover:shadow-md transition-all">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800"><?php echo e($booking->flight->flight_number); ?></p>
                                            <p class="text-xs text-slate-500"><?php echo e($booking->flight->airline->name); ?></p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-semibold text-white px-2.5 py-1 rounded-full <?php echo e($countdownClass); ?>">
                                        <?php echo e($countdownText); ?>

                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center gap-4">
                                        <div class="text-center">
                                            <p class="font-black text-slate-800"><?php echo e($departure->format('H:i')); ?></p>
                                            <p class="text-xs text-slate-500"><?php echo e($booking->flight->departureAirport->iata_code); ?></p>
                                        </div>
                                        <div class="text-slate-300">→</div>
                                        <div class="text-center">
                                            <p class="font-black text-slate-800"><?php echo e(\Carbon\Carbon::parse($booking->flight->arrival_time)->format('H:i')); ?></p>
                                            <p class="text-xs text-slate-500"><?php echo e($booking->flight->arrivalAirport->iata_code); ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-slate-500"><?php echo e($departure->format('d M Y')); ?></p>
                                        <p class="text-xs font-semibold text-slate-700"><?php echo e($booking->total_passengers); ?> org</p>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-t border-slate-100 flex justify-between items-center">
                                    <span class="text-xs font-semibold text-slate-400">Kode: <?php echo e($booking->booking_code); ?></span>
                                    <a href="<?php echo e(route('customer.booking.show', $booking)); ?>" class="text-xs font-bold text-blue-600 hover:underline">Lihat Detail</a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-slate-500 font-semibold">Belum ada perjalanan mendatang</p>
                        <a href="<?php echo e(route('customer.home')); ?>" class="text-blue-600 text-sm hover:underline mt-1 inline-block">Cari penerbangan sekarang</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-800 mb-4">Edit Profile</h2>
                
                <?php if(session('success')): ?>
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm"><?php echo e(session('success')); ?></div>
                <?php endif; ?>

                <form action="<?php echo e(route('customer.profile.update')); ?>" method="POST" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="<?php echo e(old('name', Auth::user()->name)); ?>" required
                               class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                        <input type="email" value="<?php echo e(Auth::user()->email); ?>" disabled
                               class="w-full px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-slate-500 text-sm cursor-not-allowed">
                        <p class="text-xs text-slate-400 mt-1">Email tidak bisa diubah</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">No. Telepon</label>
                        <input type="text" name="phone" value="<?php echo e(old('phone', Auth::user()->phone)); ?>"
                               class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors text-sm">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            
            <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl shadow-md p-6 text-white">
                <h3 class="font-bold mb-3">Statistik</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-blue-100 text-sm">Total Booking</span>
                        <span class="font-bold"><?php echo e($totalBookings); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-blue-100 text-sm">Selesai</span>
                        <span class="font-bold"><?php echo e($confirmedTrips); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-blue-100 text-sm">Total Pengeluaran</span>
                        <span class="font-bold">Rp <?php echo e(number_format($totalSpent, 0, ',', '.')); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\artaa\Downloads\maskapai-fixed-audit\maskapai-main\resources\views/customer/profile.blade.php ENDPATH**/ ?>