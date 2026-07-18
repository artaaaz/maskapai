<x-staff-layout title="Laporan - drgMaskapai" header="Laporan">
    <x-slot name="headerRight">
        <div class="flex items-center gap-2">
            {{-- Filter Form --}}
            <form id="exportForm" action="{{ route('staff.reports.export.csv') }}" method="GET" class="flex items-center gap-2">
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-2 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Dari">
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-2 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Sampai">
                <select name="status" class="px-2 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <div class="relative group">
                    <button type="button" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-sm transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Export
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div class="absolute right-0 top-full mt-1 bg-white rounded-xl shadow-xl border border-slate-200 py-2 min-w-[180px] hidden group-hover:block z-50">
                        <button type="submit" form="exportForm" class="w-full px-4 py-2 text-left text-sm text-slate-700 hover:bg-slate-50 flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Export Excel (.csv)
                        </button>
                        <a href="{{ route('staff.reports.print', request()->query()) }}" target="_blank" class="w-full px-4 py-2 text-left text-sm text-slate-700 hover:bg-slate-50 flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            Export PDF / Print
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </x-slot>

    @php
        $totalBookings = \App\Models\Booking::count();
        $totalPassengers = \App\Models\Passenger::count();
        $totalRevenue = \App\Models\Payment::where('payment_status', 'paid')->sum('amount');
        $pendingPayments = \App\Models\Payment::where('payment_status', 'pending')->count();
        $confirmedBookings = \App\Models\Booking::where('status', 'confirmed')->count();
        $today = \Carbon\Carbon::today();
        $boardingToday = \App\Models\Passenger::where('has_boarded', true)
            ->whereHas('booking.flight', function($q) use ($today) {
                $q->whereDate('departure_time', $today);
            })->count();
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <p class="text-slate-400 text-xs font-bold uppercase mb-2">Total Booking</p>
            <p class="text-3xl font-bold text-slate-800">{{ $totalBookings }}</p>
            <p class="text-green-600 text-xs font-semibold mt-2">{{ $confirmedBookings }} confirmed</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <p class="text-slate-400 text-xs font-bold uppercase mb-2">Total Penumpang</p>
            <p class="text-3xl font-bold text-slate-800">{{ $totalPassengers }}</p>
            <p class="text-blue-600 text-xs font-semibold mt-2">Semua waktu</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <p class="text-slate-400 text-xs font-bold uppercase mb-2">Total Pendapatan</p>
            <p class="text-3xl font-bold text-green-600">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-green-600 text-xs font-semibold mt-2">Dari pembayaran terverifikasi</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <p class="text-slate-400 text-xs font-bold uppercase mb-2">Pembayaran Pending</p>
            <p class="text-3xl font-bold text-amber-600">{{ $pendingPayments }}</p>
            <p class="text-amber-600 text-xs font-semibold mt-2">Menunggu verifikasi</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <p class="text-slate-400 text-xs font-bold uppercase mb-2">Confirmed Booking</p>
            <p class="text-3xl font-bold text-emerald-600">{{ $confirmedBookings }}</p>
            <p class="text-emerald-600 text-xs font-semibold mt-2">Terverifikasi</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <p class="text-slate-400 text-xs font-bold uppercase mb-2">Boarding Hari Ini</p>
            <p class="text-3xl font-bold text-purple-600">{{ $boardingToday }}</p>
            <p class="text-purple-600 text-xs font-semibold mt-2">Penumpang sudah boarding</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-800">Booking Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Flight</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php
                        $recentBookings = \App\Models\Booking::with(['user', 'flight.airline', 'payment'])->latest()->limit(10)->get();
                    @endphp
                    @forelse($recentBookings as $booking)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm font-bold text-slate-900">{{ $booking->booking_code }}</td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-slate-900">{{ $booking->user->name ?? 'N/A' }}</p>
                            <p class="text-xs text-slate-500">{{ $booking->user->email ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-slate-900">{{ $booking->flight->flight_number ?? 'N/A' }}</p>
                            <p class="text-xs text-slate-500">{{ $booking->flight->airline->name ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-slate-900">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($booking->payment)
                                @php
                                    $payColors = ['paid' => 'bg-green-100 text-green-700', 'pending' => 'bg-amber-100 text-amber-700', 'failed' => 'bg-red-100 text-red-700'];
                                    $payColor = $payColors[$booking->payment->payment_status] ?? 'bg-slate-100 text-slate-600';
                                @endphp
                                <span class="px-2 py-1 text-xs font-bold rounded-full {{ $payColor }}">{{ ucfirst($booking->payment->payment_status) }}</span>
                            @else
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-600">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = ['confirmed' => 'bg-green-100 text-green-700', 'pending' => 'bg-amber-100 text-amber-700', 'cancelled' => 'bg-red-100 text-red-700'];
                                $color = $statusColors[$booking->status] ?? 'bg-slate-100 text-slate-700';
                            @endphp
                            <span class="px-3 py-1 text-xs font-bold rounded-full {{ $color }}">{{ ucfirst($booking->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400 text-sm">Belum ada data booking</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-staff-layout>