<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Eksekutif Manajer - drg.Maskapai</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #7c3aed;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .logo-container {
            float: left;
        }
        .logo-title {
            font-size: 20px;
            font-weight: bold;
            color: #7c3aed;
            margin: 0;
        }
        .logo-sub {
            font-size: 10px;
            color: #666;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .meta-container {
            float: right;
            text-align: right;
        }
        .meta-item {
            margin: 0;
            font-size: 11px;
            color: #555;
        }
        .clearfix {
            clear: both;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 6px;
            margin-top: 24px;
            margin-bottom: 12px;
            text-transform: uppercase;
        }
        .stats-grid {
            margin-bottom: 20px;
        }
        .stats-card {
            width: 30%;
            float: left;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
            margin-right: 3%;
            margin-bottom: 10px;
        }
        .stats-card-last {
            margin-right: 0;
        }
        .stats-label {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .stats-value {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th {
            background-color: #f8fafc;
            border-bottom: 1px solid #cbd5e1;
            color: #475569;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 10px;
            text-align: left;
        }
        .table td {
            border-bottom: 1px solid #f1f5f9;
            padding: 8px 10px;
            color: #334155;
        }
        .table tbody tr:nth-child(even) {
            background-color: #fafafa;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-green { background-color: #d1fae5; color: #065f46; }
        .badge-yellow { background-color: #fef3c7; color: #92400e; }
        .badge-red { background-color: #fee2e2; color: #991b1b; }
        .badge-purple { background-color: #f3e8ff; color: #6b21a8; }
        .badge-gray { background-color: #e2e8f0; color: #475569; }
        
        .footer {
            margin-top: 40px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            font-size: 9px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo-container">
            <h1 class="logo-title">drg.Maskapai</h1>
            <p class="logo-sub">Manager Executive Portal</p>
        </div>
        <div class="meta-container">
            <p class="meta-item"><strong>Laporan Eksekutif</strong></p>
            <p class="meta-item">Dicetak: <?php echo e(now()->format('d/m/Y H:i')); ?></p>
            <p class="meta-item">Filter Periode: <?php echo e(strtoupper($period)); ?></p>
            <?php if($startDate && $endDate): ?>
                <p class="meta-item">Rentang: <?php echo e($startDate->format('d/m/Y')); ?> - <?php echo e($endDate->format('d/m/Y')); ?></p>
            <?php endif; ?>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="section-title">Ringkasan Metrik Utama</div>
    <div class="stats-grid">
        <div class="stats-card">
            <div class="stats-label">Total Revenue</div>
            <div class="stats-value">Rp <?php echo e(number_format($totalRevenue, 0, ',', '.')); ?></div>
        </div>
        <div class="stats-card">
            <div class="stats-label">Total Booking</div>
            <div class="stats-value"><?php echo e($totalBookings); ?></div>
        </div>
        <div class="stats-card stats-card-last">
            <div class="stats-label">Total Penumpang</div>
            <div class="stats-value"><?php echo e($totalPassengers); ?></div>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="stats-grid">
        <div class="stats-card">
            <div class="stats-label">Confirmed Bookings</div>
            <div class="stats-value" style="color: #059669;"><?php echo e($confirmedBookings); ?></div>
        </div>
        <div class="stats-card">
            <div class="stats-label">Pending Payments</div>
            <div class="stats-value" style="color: #d97706;"><?php echo e($pendingBookings); ?></div>
        </div>
        <div class="stats-card stats-card-last">
            <div class="stats-label">Cancelled Bookings</div>
            <div class="stats-value" style="color: #dc2626;"><?php echo e($cancelledBookings); ?></div>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="section-title">Top Airlines & Top Routes</div>
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 48%; padding: 0; vertical-align: top; border: none;">
                <h4 style="margin: 0 0 8px 0; color: #475569;">Top Airlines</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Airline</th>
                            <th>Bookings</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $topAirlines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $airline): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($airline->name); ?></td>
                            <td><?php echo e($airline->total_bookings); ?></td>
                            <td>Rp <?php echo e(number_format($airline->total_revenue ?? 0, 0, ',', '.')); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="3" style="text-align: center;">Tidak ada data</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </td>
            <td style="width: 4%;"></td>
            <td style="width: 48%; padding: 0; vertical-align: top; border: none;">
                <h4 style="margin: 0 0 8px 0; color: #475569;">Top Routes</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Route</th>
                            <th>Bookings</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $topRoutes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $route): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><span class="badge badge-purple"><?php echo e($route->route); ?></span></td>
                            <td><?php echo e($route->total_bookings); ?></td>
                            <td>Rp <?php echo e(number_format($route->total_revenue ?? 0, 0, ',', '.')); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="3" style="text-align: center;">Tidak ada data</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <div class="section-title">Transaksi Booking Terbaru</div>
    <table class="table">
        <thead>
            <tr>
                <th>Booking Code</th>
                <th>Customer</th>
                <th>Flight</th>
                <th>Airline</th>
                <th>Route</th>
                <th>Total Price</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $bookings->take(15); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><strong><?php echo e($b->booking_code); ?></strong></td>
                <td><?php echo e($b->user->name ?? 'N/A'); ?></td>
                <td><?php echo e($b->flight->flight_number ?? 'N/A'); ?></td>
                <td><?php echo e($b->flight->airline->name ?? 'N/A'); ?></td>
                <td>
                    <?php echo e($b->flight->departureAirport->iata_code ?? '??'); ?> → <?php echo e($b->flight->arrivalAirport->iata_code ?? '??'); ?>

                </td>
                <td>Rp <?php echo e(number_format($b->total_price, 0, ',', '.')); ?></td>
                <td>
                    <span class="badge <?php echo e($b->payment && $b->payment->payment_status === 'paid' ? 'badge-green' : ($b->payment && $b->payment->payment_status === 'pending' ? 'badge-yellow' : 'badge-red')); ?>">
                        <?php echo e($b->payment ? ucfirst($b->payment->payment_status) : 'Unpaid'); ?>

                    </span>
                </td>
                <td>
                    <span class="badge <?php echo e($b->status === 'confirmed' || $b->status === 'completed' ? 'badge-green' : ($b->status === 'pending' ? 'badge-yellow' : 'badge-red')); ?>">
                        <?php echo e(ucfirst($b->status)); ?>

                    </span>
                </td>
                <td><?php echo e($b->created_at->format('d/m/Y H:i')); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="9" style="text-align: center;">Belum ada booking</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dihasilkan secara otomatis oleh drg.Maskapai Manager Portal. Hak Cipta &copy; <?php echo e(date('Y')); ?>.
    </div>

</body>
</html>
<?php /**PATH C:\Users\artaa\Downloads\maskapai-fixed-audit\maskapai-main\resources\views/manager/reports/pdf.blade.php ENDPATH**/ ?>