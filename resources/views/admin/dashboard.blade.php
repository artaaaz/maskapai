<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - drgMaskapai</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>* { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50">
    @php
        use App\Models\Booking;
        use App\Models\Flight;
        use App\Models\Airline;
        use App\Models\Airport;
        use App\Models\Airplane;
        use App\Models\User;
        use App\Models\Passenger;
        use App\Models\Payment;
        use App\Models\ActivityLog;
        
        $totalBookings = Booking::count();
        $totalFlights = Flight::count();
        $totalAirlines = Airline::count();
        $totalAirports = Airport::count();
        $totalAirplanes = Airplane::count();
        $totalUsers = User::count();
        $totalRevenue = Payment::where('payment_status', 'paid')->sum('amount');
        $totalPassengers = Passenger::count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();
        $recentBookings = Booking::with(['user', 'flight.airline'])->latest()->limit(10)->get();
        $recentActivities = ActivityLog::with('user')->latest()->limit(10)->get();
    @endphp

    <div class="flex h-screen">
        {{-- Sidebar --}}
        <div class="w-64 bg-gradient-to-b from-slate-900 to-red-950 text-white flex flex-col">
            <div class="p-6 border-b border-white/10">
                <h1 class="text-xl font-bold">drg<span class="text-yellow-400">.</span>Maskapai</h1>
                <p class="text-red-200 text-xs mt-1">Admin Panel</p>
            </div>
            <div class="p-4 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center font-bold">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    <div>
                        <p class="font-semibold text-sm">{{ auth()->user()->name }}</p>
                        <p class="text-red-200 text-xs">Administrator</p>
                    </div>
                </div>
            </div>
            <nav class="flex-1 p-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 bg-white/10 rounded-xl text-white font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.airlines.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 rounded-xl text-red-100 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    Airlines
                </a>
                <a href="{{ route('admin.airports.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 rounded-xl text-red-100 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Airports
                </a>
                <a href="{{ route('admin.airplanes.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 rounded-xl text-red-100 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Airplanes
                </a>
                <a href="{{ route('admin.flights.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 rounded-xl text-red-100 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19V5m0 0l-7 7m7-7l7 7"></path></svg>
                    Flights
                </a>
            </nav>
            <div class="p-4 border-t border-white/10">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 rounded-xl text-red-200 hover:text-white w-full transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="flex-1 overflow-y-auto">
            <div class="p-8">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800">Dashboard Admin</h2>
                        <p class="text-slate-500">{{ now()->format('l, d F Y') }}</p>
                    </div>
                </div>

                {{-- Stats Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded">{{ $totalBookings }}</span>
                        </div>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalBookings }}</p>
                        <p class="text-slate-500 text-sm">Total Bookings</p>
                        <div class="flex gap-2 mt-2 text-xs">
                            <span class="text-green-600">{{ $confirmedBookings }} confirmed</span>
                            <span class="text-amber-600">{{ $pendingBookings }} pending</span>
                            <span class="text-red-600">{{ $cancelledBookings }} cancelled</span>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                        </div>
                        <p class="text-3xl font-bold text-slate-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        <p class="text-slate-500 text-sm">Total Revenue</p>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded">{{ $totalPassengers }}</span>
                        </div>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalPassengers }}</p>
                        <p class="text-slate-500 text-sm">Total Penumpang</p>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                            </div>
                            <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-1 rounded">{{ $totalFlights }}</span>
                        </div>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalFlights }}</p>
                        <p class="text-slate-500 text-sm">Total Penerbangan</p>
                    </div>
                </div>

                {{-- Master Data Stats --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <a href="{{ route('admin.airlines.index') }}" class="bg-white rounded-xl p-4 shadow-sm border border-slate-100 hover:border-red-200 transition flex items-center gap-4">
                        <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center text-red-600 font-bold">{{ $totalAirlines }}</div>
                        <div><p class="font-bold text-slate-800">Airlines</p><p class="text-xs text-slate-400">Maskapai terdaftar</p></div>
                    </a>
                    <a href="{{ route('admin.airports.index') }}" class="bg-white rounded-xl p-4 shadow-sm border border-slate-100 hover:border-red-200 transition flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 font-bold">{{ $totalAirports }}</div>
                        <div><p class="font-bold text-slate-800">Airports</p><p class="text-xs text-slate-400">Bandara terdaftar</p></div>
                    </a>
                    <a href="{{ route('admin.airplanes.index') }}" class="bg-white rounded-xl p-4 shadow-sm border border-slate-100 hover:border-red-200 transition flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center text-green-600 font-bold">{{ $totalAirplanes }}</div>
                        <div><p class="font-bold text-slate-800">Airplanes</p><p class="text-xs text-slate-400">Pesawat terdaftar</p></div>
                    </a>
                    <a href="#" class="bg-white rounded-xl p-4 shadow-sm border border-slate-100 hover:border-red-200 transition flex items-center gap-4">
                        <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center text-purple-600 font-bold">{{ $totalUsers }}</div>
                        <div><p class="font-bold text-slate-800">Users</p><p class="text-xs text-slate-400">Pengguna terdaftar</p></div>
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Recent Bookings --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
                        <div class="p-6 border-b border-slate-100">
                            <h3 class="text-lg font-bold text-slate-800">Booking Terbaru</h3>
                        </div>
                        <div class="p-6">
                            @forelse($recentBookings as $booking)
                                <div class="flex items-center justify-between py-3 border-b border-slate-50 last:border-0">
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $booking->booking_code }}</p>
                                        <p class="text-xs text-slate-500">{{ $booking->user->name ?? 'N/A' }} • {{ $booking->flight->airline->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-2 py-1 rounded-lg text-xs font-medium {{ $booking->status_badge['class'] }}">{{ $booking->status_badge['label'] }}</span>
                                        <p class="text-xs text-slate-400 mt-1">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-slate-400 text-center py-8">Belum ada booking</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Recent Activities --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
                        <div class="p-6 border-b border-slate-100">
                            <h3 class="text-lg font-bold text-slate-800">Aktivitas Sistem</h3>
                        </div>
                        <div class="p-6">
                            @forelse($recentActivities as $activity)
                                <div class="flex items-center gap-3 py-3 border-b border-slate-50 last:border-0">
                                    <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-xs font-bold text-slate-600">{{ substr($activity->user->name ?? 'S', 0, 1) }}</div>
                                    <div class="flex-1">
                                        <p class="text-sm text-slate-700">{{ $activity->description ?? $activity->action }}</p>
                                        <p class="text-xs text-slate-400">{{ $activity->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-slate-400 text-center py-8">Belum ada aktivitas</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>