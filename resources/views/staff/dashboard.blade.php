<x-staff-layout title="Staff Dashboard - drgMaskapai" header="Dashboard Operasional">
    <x-slot name="headerRight">
        <span class="px-3 py-1.5 bg-emerald-100 text-emerald-700 rounded-lg text-sm font-medium">{{ $todayDepartures }} Keberangkatan Hari Ini</span>
    </x-slot>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $bookingToday }}</p>
            <p class="text-slate-500 text-sm">Booking Hari Ini</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $waitingConfirmation }}</p>
            <p class="text-slate-500 text-sm">Menunggu Konfirmasi</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $passengersToday }}</p>
            <p class="text-slate-500 text-sm">Penumpang Hari Ini</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $paymentPending }}</p>
            <p class="text-slate-500 text-sm">Pembayaran Pending</p>
        </div>
    </div>

    {{-- Operational Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-slate-800">{{ $totalWaiting }}</p>
                    <p class="text-slate-500 text-xs">Waiting</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-slate-800">{{ $totalCheckedIn }}</p>
                    <p class="text-slate-500 text-xs">Checked In</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-slate-800">{{ $totalBoarded }}</p>
                    <p class="text-slate-500 text-xs">Boarded</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-slate-800">{{ $totalCompleted }}</p>
                    <p class="text-slate-500 text-xs">Completed</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-slate-800">{{ $totalNoShow }}</p>
                    <p class="text-slate-500 text-xs">No Show</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Today's Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <p class="text-2xl font-bold text-blue-600">{{ $checkedInToday }}</p>
            <p class="text-slate-500 text-xs">Check-in Hari Ini</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <p class="text-2xl font-bold text-amber-600">{{ $boardedToday }}</p>
            <p class="text-slate-500 text-xs">Boarding Hari Ini</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <p class="text-2xl font-bold text-green-600">{{ $completedToday }}</p>
            <p class="text-slate-500 text-xs">Selesai Hari Ini</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <p class="text-2xl font-bold text-red-600">{{ $noShowToday }}</p>
            <p class="text-slate-500 text-xs">No Show Hari Ini</p>
        </div>
    </div>

    {{-- Keberangkatan & Kedatangan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $todayDepartures }}</p>
                    <p class="text-slate-500 text-sm">Keberangkatan Hari Ini</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12l-9-9-9 9 9-9 9 9z"></path></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $todayArrivals }}</p>
                    <p class="text-slate-500 text-sm">Kedatangan Hari Ini</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Today's Flights --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">Jadwal Penerbangan Hari Ini</h3>
            </div>
            <div class="p-6">
                @forelse($todayFlights as $flight)
                    <div class="flex items-center justify-between py-3 border-b border-slate-50 last:border-0">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-sm font-bold text-slate-600">{{ $flight->airline->code ?? '??' }}</div>
                            <div>
                                <p class="font-semibold text-slate-800">{{ $flight->flight_number }}</p>
                                <p class="text-xs text-slate-500">{{ $flight->departureAirport->iata_code ?? '??' }} → {{ $flight->arrivalAirport->iata_code ?? '??' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-slate-800">{{ $flight->departure_time->format('H:i') }}</p>
                            <p class="text-xs {{ $flight->available_seats_count > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $flight->available_seats_count }} kursi</p>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-400 text-center py-8">Tidak ada penerbangan hari ini</p>
                @endforelse
            </div>
        </div>

        {{-- Recent Bookings --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">Booking Terbaru</h3>
            </div>
            <div class="p-6">
                @forelse($latestBookings as $booking)
                    <div class="flex items-center justify-between py-3 border-b border-slate-50 last:border-0">
                        <div>
                            <p class="font-semibold text-slate-800">{{ $booking->booking_code }}</p>
                            <p class="text-xs text-slate-500">{{ $booking->user->name ?? 'N/A' }} • {{ $booking->flight->airline->name ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 rounded-lg text-xs font-medium {{ $booking->status_badge['class'] }}">{{ $booking->status_badge['label'] }}</span>
                            <p class="text-xs text-slate-400 mt-1">{{ $booking->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-400 text-center py-8">Belum ada booking</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Activities --}}
    <div class="mt-8 bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-800">Aktivitas Terbaru</h3>
        </div>
        <div class="p-6">
            @forelse($recentActivities as $activity)
                <div class="flex items-center gap-4 py-3 border-b border-slate-50 last:border-0">
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
</x-staff-layout>