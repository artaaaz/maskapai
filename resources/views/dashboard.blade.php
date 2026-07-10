<x-admin-layout>
    <x-slot name="header">Operational Dashboard</x-slot>

    <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-3 py-1 bg-white/20 rounded-lg text-[10px] font-bold">STAFF OPERATIONS</span>
                    <span class="flex items-center gap-1 px-3 py-1 bg-green-400/20 rounded-lg text-[10px] font-bold">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        ONLINE
                    </span>
                </div>
                <h1 class="text-2xl font-bold mb-1">Hi, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
                <p class="text-emerald-100 text-sm">Kelola booking dan layani pelanggan hari ini</p>
            </div>
            <div class="hidden lg:block text-right">
                <p class="text-3xl font-bold    ">{{ now()->format('H:i') }}</p>
                <p class="text-emerald-100 text-sm">{{ now()->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    @php
        $todayBookings = \App\Models\Booking::whereDate('created_at', today())->count();
        $pendingBookings = \App\Models\Booking::where('status', 'pending')->count();
        $confirmedToday = \App\Models\Booking::where('status', 'confirmed')->whereDate('created_at', today())->count();
        $totalPassengers = \App\Models\Passenger::whereHas('booking', fn($q) => $q->whereDate('created_at', today()))->count();
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border-l-4 border-blue-500 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase">Booking Hari Ini</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $todayBookings }}</p>
                    <p class="text-blue-600 text-[10px] font-semibold mt-1">Transaksi baru</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border-l-4 border-amber-500 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase">Menunggu Konfirmasi</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $pendingBookings }}</p>
                    <p class="text-amber-600 text-[10px] font-semibold mt-1">Perlu diproses</p>
                </div>
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border-l-4 border-green-500 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase">Confirmed Hari Ini</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $confirmedToday }}</p>
                    <p class="text-green-600 text-[10px] font-semibold mt-1">Berhasil diproses</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border-l-4 border-purple-500 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase">Penumpang Hari Ini</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $totalPassengers }}</p>
                    <p class="text-purple-600 text-[10px] font-semibold mt-1">Total travelers</p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800">Booking Terbaru</h3>
                <a href="#" class="text-blue-600 text-xs font-bold hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-[10px] font-bold text-slate-500 uppercase">Kode</th>
                            <th class="px-5 py-3 text-left text-[10px] font-bold text-slate-500 uppercase">Pelanggan</th>
                            <th class="px-5 py-3 text-left text-[10px] font-bold text-slate-500 uppercase">Flight</th>
                            <th class="px-5 py-3 text-left text-[10px] font-bold text-slate-500 uppercase">Total</th>
                            <th class="px-5 py-3 text-left text-[10px] font-bold text-slate-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php
                            $bookings = \App\Models\Booking::with(['flight', 'user'])->latest()->take(5)->get();
                        @endphp
                        @forelse($bookings as $booking)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4">
                                <span class="text-xs font-bold text-slate-800">{{ $booking->booking_code }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-xs font-semibold text-slate-800">{{ $booking->user->name }}</p>
                                <p class="text-[10px] text-slate-500">{{ $booking->total_passengers }} penumpang</p>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-xs font-semibold text-slate-800">{{ $booking->flight->flight_number ?? '-' }}</p>
                                <p class="text-[10px] text-slate-500">{{ $booking->flight->departureAirport->iata_code ?? '-' }} → {{ $booking->flight->arrivalAirport->iata_code ?? '-' }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-xs font-bold text-slate-800">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-5 py-4">
                                @if($booking->status === 'confirmed')
                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded">Confirmed</span>
                                @elseif($booking->status === 'pending')
                                    <span class="px-2 py-1 bg-amber-100 text-amber-700 text-[10px] font-bold rounded">Pending</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-700 text-[10px] font-bold rounded">Cancelled</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-500 text-xs">Belum ada booking</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5">
            <h3 class="font-bold text-slate-800 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="#" class="flex items-center gap-3 p-3 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors group">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">Booking Manual</p>
                        <p class="text-[10px] text-slate-500">Tambah booking baru</p>
                    </div>
                </a>

                <a href="#" class="flex items-center gap-3 p-3 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-colors group">
                    <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">Cek Booking</p>
                        <p class="text-[10px] text-slate-500">Cari kode booking</p>
                    </div>
                </a>

                <a href="#" class="flex items-center gap-3 p-3 bg-purple-50 rounded-xl hover:bg-purple-100 transition-colors group">
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">Data Penumpang</p>
                        <p class="text-[10px] text-slate-500">Lihat semua penumpang</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-admin-layout>