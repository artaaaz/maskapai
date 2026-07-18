<x-manager-layout header="Executive Dashboard">

    {{-- Filter Bar --}}
    <div class="card mb-6">
        <div class="card-body">
            <form method="GET" action="{{ route('manager.dashboard') }}" class="flex flex-wrap items-end gap-4">
                <div>
                    <label class="form-label">Periode</label>
                    <select name="period" class="form-select" onchange="this.form.submit()" style="width:180px">
                        <option value="all" {{ $period === 'all' ? 'selected' : '' }}>Semua Waktu</option>
                        <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="year" {{ $period === 'year' ? 'selected' : '' }}>Tahun Ini</option>
                        <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Kustom</option>
                    </select>
                </div>
                <div id="customDateRange" style="display: {{ $period === 'custom' ? 'flex' : 'none' }}; gap:12px; align-items:end;">
                    <div>
                        <label class="form-label">Dari</label>
                        <input type="date" name="start_date" class="form-input" value="{{ request('start_date') }}" style="width:160px">
                    </div>
                    <div>
                        <label class="form-label">Sampai</label>
                        <input type="date" name="end_date" class="form-input" value="{{ request('end_date') }}" style="width:160px">
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

    {{-- Row 1: Operational Stats (Real-time from Staff) --}}
    <div class="grid-stats" style="margin-bottom: 1.5rem;">
        <div class="stat-card" style="border-left: 4px solid #3b82f6;">
            <div class="stat-value" style="color:#3b82f6;">{{ $totalCheckedIn ?? 0 }}</div>
            <div class="stat-label">Total Check In</div>
            <small class="text-xs text-slate-400">Hari ini: {{ $checkedInToday ?? 0 }}</small>
        </div>
        <div class="stat-card" style="border-left: 4px solid #8b5cf6;">
            <div class="stat-value" style="color:#8b5cf6;">{{ $totalBoarded ?? 0 }}</div>
            <div class="stat-label">Total Boarding</div>
            <small class="text-xs text-slate-400">Hari ini: {{ $boardedToday ?? 0 }}</small>
        </div>
        <div class="stat-card" style="border-left: 4px solid #059669;">
            <div class="stat-value" style="color:#059669;">{{ $totalCompleted ?? 0 }}</div>
            <div class="stat-label">Total Completed (Check Out)</div>
            <small class="text-xs text-slate-400">Hari ini: {{ $completedToday ?? 0 }}</small>
        </div>
        <div class="stat-card" style="border-left: 4px solid #dc2626;">
            <div class="stat-value" style="color:#dc2626;">{{ $totalNoShow ?? 0 }}</div>
            <div class="stat-label">Total No Show</div>
            <small class="text-xs text-slate-400">Hari ini: {{ $noShowToday ?? 0 }}</small>
        </div>
        <div class="stat-card" style="border-left: 4px solid #f59e0b;">
            <div class="stat-value" style="color:#f59e0b;">{{ $totalWaiting ?? 0 }}</div>
            <div class="stat-label">Menunggu Check In</div>
            <small class="text-xs text-slate-400">Belum diproses</small>
        </div>
    </div>

    {{-- Row 2: Flight Stats --}}
    <div class="grid-stats" style="margin-bottom: 1.5rem;">
        <div class="stat-card" style="border-left: 4px solid #7c3aed;">
            <div class="stat-value" style="color:#7c3aed;">{{ $totalFlights ?? 0 }}</div>
            <div class="stat-label">Total Flight</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #2563eb;">
            <div class="stat-value" style="color:#2563eb;">{{ $activeFlights ?? 0 }}</div>
            <div class="stat-label">Flight Aktif</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #059669;">
            <div class="stat-value" style="color:#059669;">{{ $completedFlights ?? 0 }}</div>
            <div class="stat-label">Flight Selesai</div>
        </div>
    </div>

    {{-- Row 3: Recent Activities --}}
    @if(isset($recentActivities) && $recentActivities->count() > 0)
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
                        @foreach($recentActivities as $log)
                        <tr>
                            <td class="text-xs text-slate-500">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $log->user?->name ?? 'System' }}</td>
                            <td>{{ $log->description ?? $log->action }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Row 4: Main Stats --}}
    <div class="grid-stats">
        <div class="stat-card">
            <div class="stat-icon" style="background:#ede9fe;">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="stat-label">Total Revenue</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="stat-value">Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}</div>
            <div class="stat-label">Revenue Bulan Ini</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe;">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div class="stat-value">{{ $totalBookings }}</div>
            <div class="stat-label">Total Booking</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:#e0f2fe;">
                <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div class="stat-value">{{ $totalPassengers }}</div>
            <div class="stat-label">Total Passenger</div>
        </div>
    </div>

    {{-- Row 2: Booking Status & Flight Today --}}
    <div class="grid-stats">
        <div class="stat-card" style="border-left: 4px solid #059669;">
            <div class="stat-value" style="color:#059669;">{{ $bookingsConfirmed }}</div>
            <div class="stat-label">Confirmed Booking</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #d97706;">
            <div class="stat-value" style="color:#d97706;">{{ $bookingsPending }}</div>
            <div class="stat-label">Pending Payment</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #dc2626;">
            <div class="stat-value" style="color:#dc2626;">{{ $bookingsCancelled }}</div>
            <div class="stat-label">Cancelled Booking</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #7c3aed;">
            <div class="stat-value" style="color:#7c3aed;">{{ $flightsToday }}</div>
            <div class="stat-label">Flight Hari Ini</div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6" style="display:grid;">
        {{-- Revenue 7 Days Chart --}}
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

        {{-- Bookings Per Month Chart --}}
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

        {{-- Payment Method Distribution --}}
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

        {{-- Booking Status Distribution --}}
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

    {{-- Revenue Summary --}}
    <div class="grid-stats">
        <div class="stat-card">
            <p class="text-xs text-slate-400 font-medium uppercase mb-1">Rata-rata Tiket</p>
            <p class="text-xl font-bold text-slate-800">Rp {{ number_format($avgTicketPrice, 0, ',', '.') }}</p>
        </div>
        <div class="stat-card">
            <p class="text-xs text-slate-400 font-medium uppercase mb-1">Rata-rata Revenue/Booking</p>
            <p class="text-xl font-bold text-slate-800">Rp {{ number_format($avgRevenuePerBooking, 0, ',', '.') }}</p>
        </div>
        <div class="stat-card">
            <p class="text-xs text-slate-400 font-medium uppercase mb-1">Success Rate</p>
            <p class="text-xl font-bold text-emerald-600">{{ $successRate }}%</p>
            <div class="w-full bg-slate-100 rounded-full h-2 mt-2">
                <div class="bg-emerald-500 h-2 rounded-full" style="width:{{ $successRate }}%"></div>
            </div>
        </div>
        <div class="stat-card">
            <p class="text-xs text-slate-400 font-medium uppercase mb-1">Rata-rata Penumpang/Booking</p>
            <p class="text-xl font-bold text-slate-800">{{ number_format($avgPassengersPerBooking, 1) }}</p>
        </div>
    </div>

    {{-- Top Routes & Top Airlines --}}
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
                            @forelse($topRoutes as $route)
                            <tr>
                                <td>
                                    <span class="badge badge-purple">{{ $route['route'] }}</span>
                                </td>
                                <td><strong>{{ $route['total_bookings'] }}</strong></td>
                                <td>Rp {{ number_format($route['total_revenue'], 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-8 text-slate-400">Belum ada data</td></tr>
                            @endforelse
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
                            @forelse($topAirlines as $airline)
                            <tr>
                                <td>
                                    <span class="font-medium text-slate-800">{{ $airline['name'] }}</span>
                                    @if($airline['code'])
                                    <span class="badge badge-gray ml-1">{{ $airline['code'] }}</span>
                                    @endif
                                </td>
                                <td><strong>{{ $airline['total_bookings'] }}</strong></td>
                                <td>Rp {{ number_format($airline['total_revenue'], 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-8 text-slate-400">Belum ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Bookings --}}
    <div class="card mb-6">
        <div class="card-header">
            <h2>Recent Bookings</h2>
            <div class="flex gap-2">
                <a href="{{ route('manager.dashboard') }}?export=pdf&period={{ $period }}{{ request('start_date') ? '&start_date='.request('start_date') : '' }}{{ request('end_date') ? '&end_date='.request('end_date') : '' }}" class="btn btn-sm btn-outline">
                    Export PDF
                </a>
                <a href="{{ route('manager.export.excel') }}?period={{ $period }}{{ request('start_date') ? '&start_date='.request('start_date') : '' }}{{ request('end_date') ? '&end_date='.request('end_date') : '' }}" class="btn btn-sm btn-outline">
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
                        @forelse($recentBookings as $b)
                        <tr>
                            <td><span class="font-bold text-slate-800">{{ $b->booking_code }}</span></td>
                            <td>{{ $b->user?->name ?? 'N/A' }}</td>
                            <td>{{ $b->flight?->flight_number ?? 'N/A' }}</td>
                            <td>
                                @if($b->flight)
                                <span class="badge badge-purple">
                                    {{ $b->flight->departureAirport?->iata_code ?? '??' }} → {{ $b->flight->arrivalAirport?->iata_code ?? '??' }}
                                </span>
                                @else
                                <span class="text-slate-400">N/A</span>
                                @endif
                            </td>
                            <td>{{ $b->flight?->airline?->name ?? 'N/A' }}</td>
                            <td><strong>Rp {{ number_format($b->total_price, 0, ',', '.') }}</strong></td>
                            <td>
                                @php
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
                                @endphp
                                <span class="badge {{ $statusClasses[$b->status] ?? 'badge-gray' }}">
                                    {{ $statusLabels[$b->status] ?? $b->status }}
                                </span>
                            </td>
                            <td>
                                @if($b->payment)
                                <span class="badge {{ $b->payment->payment_status === 'paid' ? 'badge-green' : ($b->payment->payment_status === 'pending' ? 'badge-yellow' : 'badge-red') }}">
                                    {{ ucfirst($b->payment->payment_status) }}
                                </span>
                                @else
                                <span class="badge badge-gray">—</span>
                                @endif
                            </td>
                            <td class="text-xs text-slate-500">{{ $b->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center py-8 text-slate-400">Belum ada booking</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Flight Hari Ini --}}
    <div class="card">
        <div class="card-header">
            <h2>Flight Hari Ini</h2>
            <span class="badge badge-purple">{{ $todayFlights->count() }} penerbangan</span>
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
                        @forelse($todayFlights as $f)
                        <tr>
                            <td><span class="font-bold text-slate-800">{{ $f->flight_number }}</span></td>
                            <td>{{ $f->airline?->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-purple">
                                    {{ $f->departureAirport?->iata_code ?? '??' }} → {{ $f->arrivalAirport?->iata_code ?? '??' }}
                                </span>
                            </td>
                            <td>{{ $f->departure_time->format('H:i') }}</td>
                            <td>{{ $f->arrival_time->format('H:i') }}</td>
                            <td><span class="font-bold text-green-600">{{ max(0, ($f->total_seats ?? 0) - ($f->booked_seats ?? 0)) }}</span></td>
                            <td><span class="font-bold text-amber-600">{{ $f->booked_seats ?? 0 }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-8 text-slate-400">Tidak ada penerbangan hari ini</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-manager-layout>

{{-- Chart.js Scripts --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue 7 Days Chart
    const revCtx = document.getElementById('revenueChart')?.getContext('2d');
    if (revCtx) {
        new Chart(revCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_map(fn($d) => $d['full_date'], $revenue7Days)) !!},
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: {!! json_encode(array_map(fn($d) => $d['revenue'], $revenue7Days)) !!},
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
                labels: {!! json_encode(array_map(fn($d) => $d['month'] . ' ' . $d['year'], $bookingsPerMonth)) !!},
                datasets: [{
                    label: 'Booking',
                    data: {!! json_encode(array_map(fn($d) => $d['count'], $bookingsPerMonth)) !!},
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
        const payMethods = {!! $paymentMethods->toJson() !!};
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
        const statuses = {!! $bookingStatuses->toJson() !!};
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
</write_to_file>