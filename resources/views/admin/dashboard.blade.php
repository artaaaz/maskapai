<x-admin-layout>
    <x-slot name="header">Dashboard</x-slot>

    @php
        $totalBookings = \App\Models\Booking::count();
        $totalFlights = \App\Models\Flight::count();
        $totalAirlines = \App\Models\Airline::count();
        $totalAirports = \App\Models\Airport::count();
        $totalAirplanes = \App\Models\Airplane::count();
        $totalUsers = \App\Models\User::count();
        $totalRevenue = \App\Models\Payment::where('payment_status', 'paid')->sum('amount');
        $totalPassengers = \App\Models\Passenger::count();
        $confirmedBookings = \App\Models\Booking::where('status', 'confirmed')->count();
        $pendingBookings = \App\Models\Booking::where('status', 'pending')->count();
        $cancelledBookings = \App\Models\Booking::where('status', 'cancelled')->count();
        $recentBookings = \App\Models\Booking::with(['user', 'flight.airline'])->latest()->limit(10)->get();
        $recentActivities = \App\Models\ActivityLog::with('user')->latest()->limit(10)->get();
    @endphp

    {{-- Stats Cards --}}
    <div class="grid-stats">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff;">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div class="stat-value">{{ $totalBookings }}</div>
            <div class="stat-label">Total Bookings</div>
            <div class="flex gap-3 mt-3 text-xs">
                <span class="text-green-600 font-semibold">{{ $confirmedBookings }} confirmed</span>
                <span class="text-amber-600 font-semibold">{{ $pendingBookings }} pending</span>
                <span class="text-red-600 font-semibold">{{ $cancelledBookings }} cancelled</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="stat-value">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="stat-label">Total Revenue</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:#faf5ff;">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div class="stat-value">{{ $totalPassengers }}</div>
            <div class="stat-label">Total Penumpang</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:#fefce8;">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
            </div>
            <div class="stat-value">{{ $totalFlights }}</div>
            <div class="stat-label">Total Penerbangan</div>
        </div>
    </div>

    {{-- Master Data Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('admin.airlines.index') }}" class="stat-card flex items-center gap-4 !p-4 hover:border-blue-200 transition">
            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 font-bold text-lg flex-shrink-0">{{ $totalAirlines }}</div>
            <div>
                <p class="font-bold text-slate-800">Maskapai</p>
                <p class="text-xs text-slate-400">Maskapai terdaftar</p>
            </div>
        </a>
        <a href="{{ route('admin.airports.index') }}" class="stat-card flex items-center gap-4 !p-4 hover:border-blue-200 transition">
            <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center text-green-600 font-bold text-lg flex-shrink-0">{{ $totalAirports }}</div>
            <div>
                <p class="font-bold text-slate-800">Bandara</p>
                <p class="text-xs text-slate-400">Bandara terdaftar</p>
            </div>
        </a>
        <a href="{{ route('admin.airplanes.index') }}" class="stat-card flex items-center gap-4 !p-4 hover:border-blue-200 transition">
            <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center text-purple-600 font-bold text-lg flex-shrink-0">{{ $totalAirplanes }}</div>
            <div>
                <p class="font-bold text-slate-800">Pesawat</p>
                <p class="text-xs text-slate-400">Pesawat terdaftar</p>
            </div>
        </a>
        <div class="stat-card flex items-center gap-4 !p-4">
            <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center text-amber-600 font-bold text-lg flex-shrink-0">{{ $totalUsers }}</div>
            <div>
                <p class="font-bold text-slate-800">Pengguna</p>
                <p class="text-xs text-slate-400">Pengguna terdaftar</p>
            </div>
        </div>
    </div>

    {{-- Recent Data Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Bookings --}}
        <div class="card">
            <div class="card-header">
                <h2>Booking Terbaru</h2>
            </div>
            <div class="card-body">
                @forelse($recentBookings as $booking)
                    <div class="flex items-center justify-between py-3 border-b border-slate-50 last:border-0">
                        <div>
                            <p class="font-semibold text-slate-800 text-sm">{{ $booking->booking_code }}</p>
                            <p class="text-xs text-slate-500">{{ $booking->user->name ?? 'N/A' }} • {{ $booking->flight->airline->name ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="badge {{ $booking->status_badge['class'] }}">{{ $booking->status_badge['label'] }}</span>
                            <p class="text-xs text-slate-400 mt-1">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <p class="text-slate-400">Belum ada booking</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Activities --}}
        <div class="card">
            <div class="card-header">
                <h2>Aktivitas Sistem</h2>
            </div>
            <div class="card-body">
                @forelse($recentActivities as $activity)
                    <div class="flex items-center gap-3 py-3 border-b border-slate-50 last:border-0">
                        <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-xs font-bold text-slate-600 flex-shrink-0">
                            {{ substr($activity->user->name ?? 'S', 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-slate-700">{{ $activity->description ?? $activity->action }}</p>
                            <p class="text-xs text-slate-400">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <p class="text-slate-400">Belum ada aktivitas</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-admin-layout>