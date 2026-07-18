<?php if (isset($component)) { $__componentOriginalc73f927c13cffe40bf312c0eed515659 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc73f927c13cffe40bf312c0eed515659 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.manager-layout','data' => ['header' => 'Executive Dashboard']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('manager-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['header' => 'Executive Dashboard']); ?>

    
    <div class="card mb-6">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('manager.dashboard')); ?>" class="flex flex-wrap items-end gap-4">
                <div>
                    <label class="form-label">Periode</label>
                    <select name="period" class="form-select" onchange="this.form.submit()" style="width:180px">
                        <option value="all" <?php echo e($period === 'all' ? 'selected' : ''); ?>>Semua Waktu</option>
                        <option value="today" <?php echo e($period === 'today' ? 'selected' : ''); ?>>Hari Ini</option>
                        <option value="week" <?php echo e($period === 'week' ? 'selected' : ''); ?>>Minggu Ini</option>
                        <option value="month" <?php echo e($period === 'month' ? 'selected' : ''); ?>>Bulan Ini</option>
                        <option value="year" <?php echo e($period === 'year' ? 'selected' : ''); ?>>Tahun Ini</option>
                        <option value="custom" <?php echo e($period === 'custom' ? 'selected' : ''); ?>>Kustom</option>
                    </select>
                </div>
                <div id="customDateRange" style="display: <?php echo e($period === 'custom' ? 'flex' : 'none'); ?>; gap:12px; align-items:end;">
                    <div>
                        <label class="form-label">Dari</label>
                        <input type="date" name="start_date" class="form-input" value="<?php echo e(request('start_date')); ?>" style="width:160px">
                    </div>
                    <div>
                        <label class="form-label">Sampai</label>
                        <input type="date" name="end_date" class="form-input" value="<?php echo e(request('end_date')); ?>" style="width:160px">
                    </div>
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.querySelector('[name="period"]')?.addEventListener('change', function() {
        if (this.value === 'custom') {
            document.getElementById('customDateRange').style.display = 'flex';
        } else {
            document.getElementById('customDateRange').style.display = 'none';
            this.form.submit();
        }
    });
    </script>

    
    <div class="grid-stats" style="margin-bottom: 1.5rem;">
        <div class="stat-card" style="border-left: 4px solid #3b82f6;">
            <div class="stat-value" style="color:#3b82f6;"><?php echo e($totalCheckedIn ?? 0); ?></div>
            <div class="stat-label">Total Check In</div>
            <small class="text-xs text-slate-400">Hari ini: <?php echo e($checkedInToday ?? 0); ?></small>
        </div>
        <div class="stat-card" style="border-left: 4px solid #8b5cf6;">
            <div class="stat-value" style="color:#8b5cf6;"><?php echo e($totalBoarded ?? 0); ?></div>
            <div class="stat-label">Total Boarding</div>
            <small class="text-xs text-slate-400">Hari ini: <?php echo e($boardedToday ?? 0); ?></small>
        </div>
        <div class="stat-card" style="border-left: 4px solid #059669;">
            <div class="stat-value" style="color:#059669;"><?php echo e($totalCompleted ?? 0); ?></div>
            <div class="stat-label">Total Completed (Check Out)</div>
            <small class="text-xs text-slate-400">Hari ini: <?php echo e($completedToday ?? 0); ?></small>
        </div>
        <div class="stat-card" style="border-left: 4px solid #dc2626;">
            <div class="stat-value" style="color:#dc2626;"><?php echo e($totalNoShow ?? 0); ?></div>
            <div class="stat-label">Total No Show</div>
            <small class="text-xs text-slate-400">Hari ini: <?php echo e($noShowToday ?? 0); ?></small>
        </div>
        <div class="stat-card" style="border-left: 4px solid #f59e0b;">
            <div class="stat-value" style="color:#f59e0b;"><?php echo e($totalWaiting ?? 0); ?></div>
            <div class="stat-label">Menunggu Check In</div>
            <small class="text-xs text-slate-400">Belum diproses</small>
        </div>
    </div>

    
    <div class="grid-stats" style="margin-bottom: 1.5rem;">
        <div class="stat-card" style="border-left: 4px solid #7c3aed;">
            <div class="stat-value" style="color:#7c3aed;"><?php echo e($totalFlights ?? 0); ?></div>
            <div class="stat-label">Total Flight</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #2563eb;">
            <div class="stat-value" style="color:#2563eb;"><?php echo e($activeFlights ?? 0); ?></div>
            <div class="stat-label">Flight Aktif</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #059669;">
            <div class="stat-value" style="color:#059669;"><?php echo e($completedFlights ?? 0); ?></div>
            <div class="stat-label">Flight Selesai</div>
        </div>
    </div>

    
    <?php if(isset($recentActivities) && $recentActivities->count() > 0): ?>
    <div class="card mb-6">
        <div class="card-header">
            <h2>Aktivitas Terbaru</h2>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Aktivitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="text-xs text-slate-500"><?php echo e($log->created_at->format('d/m/Y H:i')); ?></td>
                            <td><?php echo e($log->user?->name ?? 'System'); ?></td>
                            <td><?php echo e($log->description ?? $log->action); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="grid-stats">
        <div class="stat-card">
            <div class="stat-icon" style="background:#ede9fe;">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="stat-value">Rp <?php echo e(number_format($totalRevenue, 0, ',', '.')); ?></div>
            <div class="stat-label">Total Revenue</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="stat-value">Rp <?php echo e(number_format($revenueThisMonth, 0, ',', '.')); ?></div>
            <div class="stat-label">Revenue Bulan Ini</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe;">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div class="stat-value"><?php echo e($totalBookings); ?></div>
            <div class="stat-label">Total Booking</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:#e0f2fe;">
                <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div class="stat-value"><?php echo e($totalPassengers); ?></div>
            <div class="stat-label">Total Passenger</div>
        </div>
    </div>

    
    <div class="grid-stats">
        <div class="stat-card" style="border-left: 4px solid #059669;">
            <div class="stat-value" style="color:#059669;"><?php echo e($bookingsConfirmed); ?></div>
            <div class="stat-label">Confirmed Booking</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #d97706;">
            <div class="stat-value" style="color:#d97706;"><?php echo e($bookingsPending); ?></div>
            <div class="stat-label">Pending Payment</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #dc2626;">
            <div class="stat-value" style="color:#dc2626;"><?php echo e($bookingsCancelled); ?></div>
            <div class="stat-label">Cancelled Booking</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #7c3aed;">
            <div class="stat-value" style="color:#7c3aed;"><?php echo e($flightsToday); ?></div>
            <div class="stat-label">Flight Hari Ini</div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6" style="display:grid;">
        
        <div class="card">
            <div class="card-header">
                <h2>Revenue 7 Hari Terakhir</h2>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <h2>Booking per Bulan</h2>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="bookingsChart"></canvas>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <h2>Metode Pembayaran</h2>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="paymentChart"></canvas>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <h2>Distribusi Status Booking</h2>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    
    <div class="grid-stats">
        <div class="stat-card">
            <p class="text-xs text-slate-400 font-medium uppercase mb-1">Rata-rata Tiket</p>
            <p class="text-xl font-bold text-slate-800">Rp <?php echo e(number_format($avgTicketPrice, 0, ',', '.')); ?></p>
        </div>
        <div class="stat-card">
            <p class="text-xs text-slate-400 font-medium uppercase mb-1">Rata-rata Revenue/Booking</p>
            <p class="text-xl font-bold text-slate-800">Rp <?php echo e(number_format($avgRevenuePerBooking, 0, ',', '.')); ?></p>
        </div>
        <div class="stat-card">
            <p class="text-xs text-slate-400 font-medium uppercase mb-1">Success Rate</p>
            <p class="text-xl font-bold text-emerald-600"><?php echo e($successRate); ?>%</p>
            <div class="w-full bg-slate-100 rounded-full h-2 mt-2">
                <div class="bg-emerald-500 h-2 rounded-full" style="width:<?php echo e($successRate); ?>%"></div>
            </div>
        </div>
        <div class="stat-card">
            <p class="text-xs text-slate-400 font-medium uppercase mb-1">Rata-rata Penumpang/Booking</p>
            <p class="text-xl font-bold text-slate-800"><?php echo e(number_format($avgPassengersPerBooking, 1)); ?></p>
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6" style="display:grid;">
        <div class="card">
            <div class="card-header">
                <h2>Top Routes</h2>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th>Rute</th>
                                <th>Total Booking</th>
                                <th>Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $topRoutes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $route): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <span class="badge badge-purple"><?php echo e($route['route']); ?></span>
                                </td>
                                <td><strong><?php echo e($route['total_bookings']); ?></strong></td>
                                <td>Rp <?php echo e(number_format($route['total_revenue'], 0, ',', '.')); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="3" class="text-center py-8 text-slate-400">Belum ada data</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>Top Airlines</h2>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th>Maskapai</th>
                                <th>Total Booking</th>
                                <th>Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $topAirlines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $airline): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <span class="font-medium text-slate-800"><?php echo e($airline['name']); ?></span>
                                    <?php if($airline['code']): ?>
                                    <span class="badge badge-gray ml-1"><?php echo e($airline['code']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo e($airline['total_bookings']); ?></strong></td>
                                <td>Rp <?php echo e(number_format($airline['total_revenue'], 0, ',', '.')); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="3" class="text-center py-8 text-slate-400">Belum ada data</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card mb-6">
        <div class="card-header">
            <h2>Recent Bookings</h2>
            <div class="flex gap-2">
                <a href="<?php echo e(route('manager.dashboard')); ?>?export=pdf&period=<?php echo e($period); ?><?php echo e(request('start_date') ? '&start_date='.request('start_date') : ''); ?><?php echo e(request('end_date') ? '&end_date='.request('end_date') : ''); ?>" class="btn btn-sm btn-outline">
                    Export PDF
                </a>
                <a href="<?php echo e(route('manager.export.excel')); ?>?period=<?php echo e($period); ?><?php echo e(request('start_date') ? '&start_date='.request('start_date') : ''); ?><?php echo e(request('end_date') ? '&end_date='.request('end_date') : ''); ?>" class="btn btn-sm btn-outline">
                    Export Excel
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th>Kode Booking</th>
                            <th>Customer</th>
                            <th>Flight</th>
                            <th>Route</th>
                            <th>Maskapai</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><span class="font-bold text-slate-800"><?php echo e($b->booking_code); ?></span></td>
                            <td><?php echo e($b->user?->name ?? 'N/A'); ?></td>
                            <td><?php echo e($b->flight?->flight_number ?? 'N/A'); ?></td>
                            <td>
                                <?php if($b->flight): ?>
                                <span class="badge badge-purple">
                                    <?php echo e($b->flight->departureAirport?->iata_code ?? '??'); ?> → <?php echo e($b->flight->arrivalAirport?->iata_code ?? '??'); ?>

                                </span>
                                <?php else: ?>
                                <span class="text-slate-400">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($b->flight?->airline?->name ?? 'N/A'); ?></td>
                            <td><strong>Rp <?php echo e(number_format($b->total_price, 0, ',', '.')); ?></strong></td>
                            <td>
                                <?php
                                    $statusClasses = [
                                        'pending' => 'badge-yellow',
                                        'confirmed' => 'badge-green',
                                        'in_progress' => 'badge-blue',
                                        'completed' => 'badge-green',
                                        'cancelled' => 'badge-red',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Pending Payment',
                                        'confirmed' => 'Confirmed',
                                        'in_progress' => 'In Progress',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                    ];
                                ?>
                                <span class="badge <?php echo e($statusClasses[$b->status] ?? 'badge-gray'); ?>">
                                    <?php echo e($statusLabels[$b->status] ?? $b->status); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($b->payment): ?>
                                <span class="badge <?php echo e($b->payment->payment_status === 'paid' ? 'badge-green' : ($b->payment->payment_status === 'pending' ? 'badge-yellow' : 'badge-red')); ?>">
                                    <?php echo e(ucfirst($b->payment->payment_status)); ?>

                                </span>
                                <?php else: ?>
                                <span class="badge badge-gray">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-xs text-slate-500"><?php echo e($b->created_at->format('d/m/Y H:i')); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="9" class="text-center py-8 text-slate-400">Belum ada booking</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <h2>Flight Hari Ini</h2>
            <span class="badge badge-purple"><?php echo e($todayFlights->count()); ?> penerbangan</span>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th>Flight</th>
                            <th>Maskapai</th>
                            <th>Route</th>
                            <th>Berangkat</th>
                            <th>Tiba</th>
                            <th>Kursi Tersedia</th>
                            <th>Kursi Terbooking</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $todayFlights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><span class="font-bold text-slate-800"><?php echo e($f->flight_number); ?></span></td>
                            <td><?php echo e($f->airline?->name ?? 'N/A'); ?></td>
                            <td>
                                <span class="badge badge-purple">
                                    <?php echo e($f->departureAirport?->iata_code ?? '??'); ?> → <?php echo e($f->arrivalAirport?->iata_code ?? '??'); ?>

                                </span>
                            </td>
                            <td><?php echo e($f->departure_time->format('H:i')); ?></td>
                            <td><?php echo e($f->arrival_time->format('H:i')); ?></td>
                            <td><span class="font-bold text-green-600"><?php echo e(max(0, ($f->total_seats ?? 0) - ($f->booked_seats ?? 0))); ?></span></td>
                            <td><span class="font-bold text-amber-600"><?php echo e($f->booked_seats ?? 0); ?></span></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7" class="text-center py-8 text-slate-400">Tidak ada penerbangan hari ini</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc73f927c13cffe40bf312c0eed515659)): ?>
<?php $attributes = $__attributesOriginalc73f927c13cffe40bf312c0eed515659; ?>
<?php unset($__attributesOriginalc73f927c13cffe40bf312c0eed515659); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc73f927c13cffe40bf312c0eed515659)): ?>
<?php $component = $__componentOriginalc73f927c13cffe40bf312c0eed515659; ?>
<?php unset($__componentOriginalc73f927c13cffe40bf312c0eed515659); ?>
<?php endif; ?>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue 7 Days Chart
    const revCtx = document.getElementById('revenueChart')?.getContext('2d');
    if (revCtx) {
        new Chart(revCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_map(fn($d) => $d['full_date'], $revenue7Days)); ?>,
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: <?php echo json_encode(array_map(fn($d) => $d['revenue'], $revenue7Days)); ?>,
                    backgroundColor: '#8b5cf6',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => 'Rp' + (v/1000).toFixed(0) + 'K' }
                    }
                }
            }
        });
    }

    // Bookings Per Month Chart
    const bookCtx = document.getElementById('bookingsChart')?.getContext('2d');
    if (bookCtx) {
        new Chart(bookCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_map(fn($d) => $d['month'] . ' ' . $d['year'], $bookingsPerMonth)); ?>,
                datasets: [{
                    label: 'Booking',
                    data: <?php echo json_encode(array_map(fn($d) => $d['count'], $bookingsPerMonth)); ?>,
                    borderColor: '#7c3aed',
                    backgroundColor: 'rgba(124,58,237,0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#7c3aed',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    }

    // Payment Method Chart
    const payCtx = document.getElementById('paymentChart')?.getContext('2d');
    if (payCtx) {
        const payMethods = <?php echo $paymentMethods->toJson(); ?>;
        const colors = ['#8b5cf6', '#3b82f6', '#10b981', '#f59e0b', '#ef4444'];
        new Chart(payCtx, {
            type: 'doughnut',
            data: {
                labels: payMethods.map(m => m.payment_method || 'Unknown'),
                datasets: [{
                    data: payMethods.map(m => m.total),
                    backgroundColor: colors.slice(0, payMethods.length),
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12, font: { size: 11 } } }
                }
            }
        });
    }

    // Booking Status Chart
    const statCtx = document.getElementById('statusChart')?.getContext('2d');
    if (statCtx) {
        const statuses = <?php echo $bookingStatuses->toJson(); ?>;
        const statusColors = {
            pending: '#f59e0b',
            confirmed: '#10b981',
            in_progress: '#3b82f6',
            completed: '#059669',
            cancelled: '#ef4444'
        };
        const statusLabels = {
            pending: 'Pending Payment',
            confirmed: 'Confirmed',
            in_progress: 'In Progress',
            completed: 'Completed',
            cancelled: 'Cancelled'
        };
        new Chart(statCtx, {
            type: 'pie',
            data: {
                labels: statuses.map(s => statusLabels[s.status] || s.status),
                datasets: [{
                    data: statuses.map(s => s.total),
                    backgroundColor: statuses.map(s => statusColors[s.status] || '#94a3b8'),
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12, font: { size: 11 } } }
                }
            }
        });
    }
});
</script>
</write_to_file><?php /**PATH C:\Users\artaa\Downloads\maskapai-fixed-audit\maskapai-main\resources\views/manager/dashboard.blade.php ENDPATH**/ ?>