<?php if (isset($component)) { $__componentOriginalc73f927c13cffe40bf312c0eed515659 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc73f927c13cffe40bf312c0eed515659 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.manager-layout','data' => ['header' => 'Laporan Eksekutif & Analitik']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('manager-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['header' => 'Laporan Eksekutif & Analitik']); ?>

    
    <div class="card mb-6">
        <div class="card-body flex flex-wrap items-end justify-between gap-4">
            <form method="GET" action="<?php echo e(route('manager.reports')); ?>" class="flex flex-wrap items-end gap-4">
                <div>
                    <label class="form-label">Periode Analisis</label>
                    <select name="period" class="form-select" onchange="this.form.submit()" style="width:180px">
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

            <div class="flex gap-2">
                <a href="<?php echo e(route('manager.reports.export.pdf')); ?>?period=<?php echo e($period); ?><?php echo e(request('start_date') ? '&start_date='.request('start_date') : ''); ?><?php echo e(request('end_date') ? '&end_date='.request('end_date') : ''); ?>" class="btn btn-danger">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export PDF
                </a>
                <a href="<?php echo e(route('manager.reports.export.excel')); ?>?period=<?php echo e($period); ?><?php echo e(request('start_date') ? '&start_date='.request('start_date') : ''); ?><?php echo e(request('end_date') ? '&end_date='.request('end_date') : ''); ?>" class="btn btn-success">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Excel
                </a>
            </div>
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

    
    <div class="grid-stats">
        <div class="stat-card">
            <div class="stat-icon" style="background:#ede9fe;">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="stat-value">Rp <?php echo e(number_format($totalRevenue, 0, ',', '.')); ?></div>
            <div class="stat-label">Total Revenue (Terfilter)</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div class="stat-value">Rp <?php echo e(number_format($revenueThisMonth, 0, ',', '.')); ?></div>
            <div class="stat-label">Revenue Bulan Ini</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe;">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div class="stat-value"><?php echo e($totalBookings); ?></div>
            <div class="stat-label">Total Booking (Terfilter)</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:#e0f2fe;">
                <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div class="stat-value"><?php echo e($totalPassengers); ?></div>
            <div class="stat-label">Total Passenger (Terfilter)</div>
        </div>
    </div>

    
    <div class="grid-stats">
        <div class="stat-card" style="border-left: 4px solid #059669;">
            <div class="stat-value" style="color:#059669;"><?php echo e($bookingsConfirmed); ?></div>
            <div class="stat-label">Confirmed Booking (Terfilter)</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #d97706;">
            <div class="stat-value" style="color:#d97706;"><?php echo e($bookingsPending); ?></div>
            <div class="stat-label">Pending Payment (Terfilter)</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #dc2626;">
            <div class="stat-value" style="color:#dc2626;"><?php echo e($bookingsCancelled); ?></div>
            <div class="stat-label">Cancelled Booking (Terfilter)</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #4f46e5;">
            <div class="stat-value" style="color:#4f46e5;"><?php echo e($totalFlights); ?></div>
            <div class="stat-label">Total Flights (Terfilter)</div>
        </div>
    </div>

    
    <div class="grid-stats">
        <div class="stat-card">
            <p class="text-xs text-slate-400 font-medium uppercase mb-1">Check In (Terfilter)</p>
            <p class="text-2xl font-bold text-blue-600"><?php echo e($checkInToday); ?></p>
        </div>
        <div class="stat-card">
            <p class="text-xs text-slate-400 font-medium uppercase mb-1">Boarding (Terfilter)</p>
            <p class="text-2xl font-bold text-amber-600"><?php echo e($boardingToday); ?></p>
        </div>
        <div class="stat-card">
            <p class="text-xs text-slate-400 font-medium uppercase mb-1">Flight (Terfilter)</p>
            <p class="text-2xl font-bold text-purple-600"><?php echo e($flightsTodayCount); ?></p>
        </div>
        <div class="stat-card">
            <p class="text-xs text-slate-400 font-medium uppercase mb-1">Total Maskapai</p>
            <p class="text-2xl font-bold text-slate-800"><?php echo e($totalAirlines); ?></p>
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(45%, 1fr)); gap: 24px;">
        
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
                <h2>Distribusi Status Booking</h2>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <h2>Distribusi Metode Pembayaran</h2>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="paymentChart"></canvas>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <h2>Booking berdasarkan Maskapai</h2>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="airlineChart"></canvas>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <h2>Booking berdasarkan Rute</h2>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="routeChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(45%, 1fr)); gap: 24px;">
        <div class="card">
            <div class="card-header">
                <h2>Top Airlines</h2>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th>Nama Maskapai</th>
                                <th>Total Booking</th>
                                <th>Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $topAirlines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $airline): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <span class="font-medium text-slate-800"><?php echo e($airline->name); ?></span>
                                    <?php if($airline->code): ?>
                                    <span class="badge badge-gray ml-1"><?php echo e($airline->code); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo e($airline->total_bookings); ?></strong></td>
                                <td>Rp <?php echo e(number_format($airline->total_revenue ?? 0, 0, ',', '.')); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="3" class="text-center py-8 text-slate-400">Tidak ada data</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

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
                                    <span class="badge badge-purple"><?php echo e($route->route); ?></span>
                                </td>
                                <td><strong><?php echo e($route->total_bookings); ?></strong></td>
                                <td>Rp <?php echo e(number_format($route->total_revenue ?? 0, 0, ',', '.')); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="3" class="text-center py-8 text-slate-400">Tidak ada data</td></tr>
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
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th>Booking Code</th>
                            <th>Customer</th>
                            <th>Flight Number</th>
                            <th>Airline</th>
                            <th>Route</th>
                            <th>Total</th>
                            <th>Payment Status</th>
                            <th>Booking Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><span class="font-bold text-slate-800"><?php echo e($b->booking_code); ?></span></td>
                            <td><?php echo e($b->user->name ?? 'N/A'); ?></td>
                            <td><?php echo e($b->flight->flight_number ?? 'N/A'); ?></td>
                            <td><?php echo e($b->flight->airline->name ?? 'N/A'); ?></td>
                            <td>
                                <?php if($b->flight): ?>
                                <span class="badge badge-purple">
                                    <?php echo e($b->flight->departureAirport->iata_code ?? '??'); ?> → <?php echo e($b->flight->arrivalAirport->iata_code ?? '??'); ?>

                                </span>
                                <?php else: ?>
                                <span class="text-slate-400">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td><strong>Rp <?php echo e(number_format($b->total_price, 0, ',', '.')); ?></strong></td>
                            <td>
                                <?php if($b->payment): ?>
                                <span class="badge <?php echo e($b->payment->payment_status === 'paid' ? 'badge-green' : ($b->payment->payment_status === 'pending' ? 'badge-yellow' : 'badge-red')); ?>">
                                    <?php echo e(ucfirst($b->payment->payment_status)); ?>

                                </span>
                                <?php else: ?>
                                <span class="badge badge-red">Unpaid</span>
                                <?php endif; ?>
                            </td>
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

    
    <div class="card mb-6">
        <div class="card-header">
            <h2>Recent Payments</h2>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th>Transaction Code</th>
                            <th>Booking Code</th>
                            <th>Customer</th>
                            <th>Payment Method</th>
                            <th>Amount</th>
                            <th>Payment Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><span class="font-bold text-slate-800"><?php echo e($p->transaction_code); ?></span></td>
                            <td><?php echo e($p->booking->booking_code ?? 'N/A'); ?></td>
                            <td><?php echo e($p->booking->user->name ?? 'N/A'); ?></td>
                            <td><span class="badge badge-gray"><?php echo e(ucfirst(str_replace('_', ' ', $p->payment_method))); ?></span></td>
                            <td><strong>Rp <?php echo e(number_format($p->amount, 0, ',', '.')); ?></strong></td>
                            <td>
                                <span class="badge <?php echo e($p->payment_status === 'paid' ? 'badge-green' : ($p->payment_status === 'pending' ? 'badge-yellow' : 'badge-red')); ?>">
                                    <?php echo e(ucfirst($p->payment_status)); ?>

                                </span>
                            </td>
                            <td class="text-xs text-slate-500"><?php echo e($p->created_at->format('d/m/Y H:i')); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7" class="text-center py-8 text-slate-400">Belum ada transaksi pembayaran</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <h2>Flight Hari Ini</h2>
            <span class="badge badge-purple"><?php echo e($todayFlightsList->count()); ?> Penerbangan Hari Ini</span>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th>Flight Number</th>
                            <th>Airline</th>
                            <th>Route</th>
                            <th>Departure Time</th>
                            <th>Arrival Time</th>
                            <th>Available Seats</th>
                            <th>Total Passenger</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $todayFlightsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><span class="font-bold text-slate-800"><?php echo e($f->flight_number); ?></span></td>
                            <td><?php echo e($f->airline->name ?? 'N/A'); ?></td>
                            <td>
                                <span class="badge badge-purple">
                                    <?php echo e($f->departureAirport->iata_code ?? '??'); ?> → <?php echo e($f->arrivalAirport->iata_code ?? '??'); ?>

                                </span>
                            </td>
                            <td><?php echo e($f->departure_time->format('H:i')); ?></td>
                            <td><?php echo e($f->arrival_time->format('H:i')); ?></td>
                            <td><span class="font-bold text-green-600"><?php echo e(max(0, $f->total_seats - $f->booked_seats)); ?></span></td>
                            <td><span class="font-bold text-amber-600"><?php echo e($f->booked_seats); ?></span></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7" class="text-center py-8 text-slate-400">Tidak ada penerbangan terjadwal hari ini</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Revenue 7 Days Chart
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
                            ticks: { callback: v => 'Rp ' + (v/1000).toFixed(0) + 'K' }
                        }
                    }
                }
            });
        }

        // 2. Bookings Per Month Chart
        const bookCtx = document.getElementById('bookingsChart')?.getContext('2d');
        if (bookCtx) {
            new Chart(bookCtx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode(array_map(fn($d) => $d['month'] . ' ' . $d['year'], $bookingsPerMonth)); ?>,
                    datasets: [{
                        label: 'Bookings',
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

        // 3. Booking Status Chart
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
                        legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12 } }
                    }
                }
            });
        }

        // 4. Payment Method Chart
        const payCtx = document.getElementById('paymentChart')?.getContext('2d');
        if (payCtx) {
            const payMethods = <?php echo $paymentMethods->toJson(); ?>;
            const colors = ['#8b5cf6', '#3b82f6', '#10b981', '#f59e0b', '#ef4444'];
            new Chart(payCtx, {
                type: 'doughnut',
                data: {
                    labels: payMethods.map(m => m.payment_method ? m.payment_method.toUpperCase().replace('_', ' ') : 'Unknown'),
                    datasets: [{
                        data: payMethods.map(m => m.total),
                        backgroundColor: colors.slice(0, payMethods.length),
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12 } }
                    }
                }
            });
        }

        // 5. Booking By Airline Chart
        const airCtx = document.getElementById('airlineChart')?.getContext('2d');
        if (airCtx) {
            const airlines = <?php echo $bookingsByAirline->toJson(); ?>;
            new Chart(airCtx, {
                type: 'bar',
                data: {
                    labels: airlines.map(a => a.name),
                    datasets: [{
                        label: 'Bookings',
                        data: airlines.map(a => a.total),
                        backgroundColor: '#6366f1',
                        borderRadius: 4,
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

        // 6. Booking By Route Chart
        const routeCtx = document.getElementById('routeChart')?.getContext('2d');
        if (routeCtx) {
            const routes = <?php echo $bookingsByRoute->toJson(); ?>;
            new Chart(routeCtx, {
                type: 'bar',
                data: {
                    labels: routes.map(r => r.route),
                    datasets: [{
                        label: 'Bookings',
                        data: routes.map(r => r.total),
                        backgroundColor: '#ec4899',
                        borderRadius: 4,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
                }
            });
        }
    });
    </script>
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
<?php /**PATH C:\Users\artaa\Downloads\maskapai-fixed-audit\maskapai-main\resources\views/manager/reports/index.blade.php ENDPATH**/ ?>