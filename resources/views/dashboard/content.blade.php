{{-- Welcome Banner --}}
<div class="bg-gradient-to-r from-blue-600 via-blue-700 to-blue-900 rounded-2xl shadow-xl p-8 mb-8 text-white border-b-4 border-blue-400">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
            <p class="text-blue-100 text-lg font-medium">
                @if(Auth::user()->role === 'admin')
                    Kelola master data sistem penerbangan
                @elseif(Auth::user()->role === 'staff')
                    Kelola operasional penerbangan harian
                @elseif(Auth::user()->role === 'manager')
                    Monitor performa dan pendapatan sistem
                @else
                    Siap untuk perjalanan Anda selanjutnya?
                @endif
            </p>
        </div>
        <div class="hidden md:block">
            <div class="w-20 h-20 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

{{-- Statistics Cards --}}
@php
    $totalFlights = \App\Models\Flight::count();
    $totalAirlines = \App\Models\Airline::count();
    $totalAirports = \App\Models\Airport::count();
    $totalBookings = \App\Models\Booking::count();
    $userBookings = \App\Models\Booking::where('user_id', Auth::id())->count();
    $totalRevenue = \App\Models\Payment::where('payment_status', 'paid')->sum('amount');
@endphp

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    {{-- Total Flights --}}
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-600 hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-bold uppercase">Total Penerbangan</p>
                <p class="text-3xl font-black text-gray-900 mt-1">{{ $totalFlights }}</p>
                <p class="text-blue-600 text-xs font-semibold mt-1">Flight aktif</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
            </div>
        </div>
    </div>

    {{-- Total Airlines --}}
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-600 hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-bold uppercase">Maskapai</p>
                <p class="text-3xl font-black text-gray-900 mt-1">{{ $totalAirlines }}</p>
                <p class="text-green-600 text-xs font-semibold mt-1">Partner airlines</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>
    </div>

    {{-- Total Airports --}}
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-600 hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-bold uppercase">Bandara</p>
                <p class="text-3xl font-black text-gray-900 mt-1">{{ $totalAirports }}</p>
                <p class="text-purple-600 text-xs font-semibold mt-1">Destinasi</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    {{-- Total Bookings/Revenue --}}
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-600 hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-bold uppercase">{{ Auth::user()->role === 'manager' ? 'Total Pendapatan' : (Auth::user()->role === 'customer' ? 'Booking Saya' : 'Total Booking') }}</p>
                <p class="text-3xl font-black text-gray-900 mt-1">
                    @if(Auth::user()->role === 'manager')
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    @elseif(Auth::user()->role === 'customer')
                        {{ $userBookings }}
                    @else
                        {{ $totalBookings }}
                    @endif
                </p>
                <p class="text-orange-600 text-xs font-semibold mt-1">Transaksi</p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

{{-- Activity Section --}}
<div class="bg-white rounded-2xl shadow-lg p-8 border-t-4 border-blue-600">
    <h3 class="text-2xl font-black text-gray-900 mb-6 flex items-center">
        <span class="w-2 h-8 bg-blue-600 rounded-full mr-3"></span>
        Aktivitas Terbaru
    </h3>
    
    <div class="text-center py-12 bg-gradient-to-b from-blue-50 to-white rounded-xl border-2 border-blue-200">
        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
        </div>
        <p class="text-gray-800 font-bold text-lg mb-2">Dashboard siap digunakan</p>
        <p class="text-gray-600 font-medium">Silakan pilih menu di sidebar untuk mengelola data</p>
        <div class="mt-6 flex justify-center space-x-4">
            <div class="px-6 py-3 bg-blue-100 rounded-xl">
                <p class="text-blue-800 font-bold text-sm">Total Data</p>
                <p class="text-blue-900 font-black text-2xl">{{ $totalFlights + $totalAirlines + $totalAirports }}</p>
            </div>
            <div class="px-6 py-3 bg-green-100 rounded-xl">
                <p class="text-green-800 font-bold text-sm">Status</p>
                <p class="text-green-900 font-black text-sm">Aktif</p>
            </div>
        </div>
    </div>
</div>