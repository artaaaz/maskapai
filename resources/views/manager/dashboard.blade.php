<x-admin-layout>
    <x-slot name="header">Executive Dashboard</x-slot>

    <div class="bg-gradient-to-br from-purple-600 to-indigo-800 rounded-2xl shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-3 py-1 bg-white/20 rounded-lg text-[10px] font-bold">MANAGER</span>
                    <span class="flex items-center gap-1 px-3 py-1 bg-green-400/20 rounded-lg text-[10px] font-bold">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        MONITORING
                    </span>
                </div>
                <h1 class="text-2xl font-bold mb-1">Dashboard Performa 📊</h1>
                <p class="text-purple-100 text-sm">Monitor revenue dan operasional sistem</p>
            </div>
            <div class="hidden lg:block text-right">
                <p class="text-sm text-purple-100">Bulan Ini</p>
                <p class="text-2xl font-bold">{{ now()->format('F Y') }}</p>
            </div>
        </div>
    </div>

    @php
        $totalRevenue = \App\Models\Payment::where('payment_status', 'paid')->sum('amount');
        $monthlyRevenue = \App\Models\Payment::where('payment_status', 'paid')->whereMonth('created_at', now()->month)->sum('amount');
        $totalBookings = \App\Models\Booking::count();
        $confirmedBookings = \App\Models\Booking::where('status', 'confirmed')->count();
        $successRate = $totalBookings > 0 ? round(($confirmedBookings / $totalBookings) * 100) : 0;
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-5 border-t-4 border-green-500">
            <p class="text-slate-400 text-[10px] font-bold uppercase mb-1">Total Pendapatan</p>
            <p class="text-2xl font-bold text-slate-800">Rp {{ number_format($totalRevenue / 1000000, 1) }}Jt</p>
            <div class="flex items-center gap-1 mt-2">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                <span class="text-green-600 text-[10px] font-bold">+12% dari bulan lalu</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 border-t-4 border-blue-500">
            <p class="text-slate-400 text-[10px] font-bold uppercase mb-1">Revenue Bulan Ini</p>
            <p class="text-2xl font-bold text-slate-800">Rp {{ number_format($monthlyRevenue / 1000, 0) }}K</p>
            <p class="text-blue-600 text-[10px] font-bold mt-2">{{ now()->format('M Y') }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 border-t-4 border-purple-500">
            <p class="text-slate-400 text-[10px] font-bold uppercase mb-1">Success Rate</p>
            <p class="text-2xl font-bold text-slate-800">{{ $successRate }}%</p>
            <p class="text-purple-600 text-[10px] font-bold mt-2">{{ $confirmedBookings }} dari {{ $totalBookings }} booking</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 border-t-4 border-amber-500">
            <p class="text-slate-400 text-[10px] font-bold uppercase mb-1">Total Booking</p>
            <p class="text-2xl font-bold text-slate-800">{{ $totalBookings }}</p>
            <p class="text-amber-600 text-[10px] font-bold mt-2">Semua waktu</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5">
            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <span class="w-1.5 h-5 bg-purple-600 rounded-full"></span>
                Top Routes
            </h3>
            <div class="space-y-3">
                @php
                    $routes = [
                        ['from' => 'CGK', 'to' => 'DPS', 'flights' => 45, 'revenue' => 125000000],
                        ['from' => 'CGK', 'to' => 'SUB', 'flights' => 38, 'revenue' => 95000000],
                        ['from' => 'DPS', 'to' => 'CGK', 'flights' => 35, 'revenue' => 88000000],
                    ];
                @endphp
                @foreach($routes as $index => $route)
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <span class="text-xs font-bold text-purple-600">{{ $index + 1 }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $route['from'] }} → {{ $route['to'] }}</p>
                            <p class="text-[10px] text-slate-500">{{ $route['flights'] }} flights</p>
                        </div>
                    </div>
                    <p class="text-sm font-bold text-slate-800">Rp {{ number_format($route['revenue'] / 1000000, 1) }}Jt</p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5">
            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <span class="w-1.5 h-5 bg-blue-600 rounded-full"></span>
                Top Airlines
            </h3>
            <div class="space-y-3">
                @php
                    $airlines = \App\Models\Airline::withCount('flights')->orderByDesc('flights_count')->take(3)->get();
                @endphp
                @forelse($airlines as $index => $airline)
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <span class="text-xs font-bold text-blue-600">{{ $index + 1 }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $airline->name }}</p>
                            <p class="text-[10px] text-slate-500">{{ $airline->code }}</p>
                        </div>
                    </div>
                    <p class="text-sm font-bold text-slate-800">{{ $airline->flights_count }} flights</p>
                </div>
                @empty
                <p class="text-slate-500 text-xs text-center py-4">Belum ada data</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5">
        <h3 class="font-bold text-slate-800 mb-4">System Activity</h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border-l-4 border-green-500">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <p class="text-sm font-medium text-slate-800">Payment received - BK001234</p>
                </div>
                <span class="text-[10px] text-slate-500">5 min ago</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                    <p class="text-sm font-medium text-slate-800">New booking confirmed - GA123</p>
                </div>
                <span class="text-[10px] text-slate-500">15 min ago</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg border-l-4 border-purple-500">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                    <p class="text-sm font-medium text-slate-800">Schedule updated - Flight GA456</p>
                </div>
                <span class="text-[10px] text-slate-500">1 hour ago</span>
            </div>
        </div>
    </div>
</x-admin-layout>