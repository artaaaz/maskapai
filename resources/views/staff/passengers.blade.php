<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Penumpang - drgMaskapai</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>* { font-family: 'IBM Plex Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50">
    <div class="flex h-screen">
        <div class="w-64 bg-gradient-to-b from-teal-900 to-emerald-900 text-white flex flex-col">
            <div class="p-6 border-b border-white/10">
                <h1 class="text-xl font-bold">drg<span class="text-yellow-400">.</span>Maskapai</h1>
                <p class="text-emerald-200 text-xs mt-1">Staff Portal</p>
            </div>
            <div class="p-4 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center font-bold">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    <div>
                        <p class="font-semibold text-sm">{{ auth()->user()->name }}</p>
                        <p class="text-emerald-200 text-xs">Staff</p>
                    </div>
                </div>
            </div>
            <nav class="flex-1 p-4 space-y-1">
                <a href="{{ route('staff.dashboard') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 rounded-xl text-emerald-100 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>
                <a href="{{ route('staff.bookings') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 rounded-xl text-emerald-100 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    Bookings
                </a>
                <a href="{{ route('staff.passengers') }}" class="flex items-center gap-3 px-4 py-3 bg-white/10 rounded-xl text-white font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Penumpang
                </a>
                <a href="{{ route('staff.reports') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 rounded-xl text-emerald-100 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Laporan
                </a>
            </nav>
            <div class="p-4 border-t border-white/10">
                <form method="POST" action="{{ route('staff.logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 rounded-xl text-emerald-200 hover:text-white w-full transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto">
            <div class="p-8">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800">Data Penumpang</h2>
                        <p class="text-slate-500">Total {{ $passengers->total() }} penumpang</p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm">{{ session('success') }}</div>
                @endif

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100">
                                    <th class="text-left p-4 text-sm font-semibold text-slate-600">Nama</th>
                                    <th class="text-left p-4 text-sm font-semibold text-slate-600">Booking</th>
                                    <th class="text-left p-4 text-sm font-semibold text-slate-600">Penerbangan</th>
                                    <th class="text-left p-4 text-sm font-semibold text-slate-600">Kursi</th>
                                    <th class="text-left p-4 text-sm font-semibold text-slate-600">Status</th>
                                    <th class="text-left p-4 text-sm font-semibold text-slate-600">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($passengers as $passenger)
                                    <tr class="border-b border-slate-50 hover:bg-slate-50">
                                        <td class="p-4">
                                            <p class="font-semibold text-slate-800">{{ $passenger->full_name_with_title }}</p>
                                            <p class="text-xs text-slate-400">{{ $passenger->passport_number }}</p>
                                        </td>
                                        <td class="p-4">
                                            <p class="text-sm font-medium text-slate-700">{{ $passenger->booking->booking_code ?? 'N/A' }}</p>
                                            <p class="text-xs text-slate-400">{{ $passenger->booking->created_at->format('d/m/Y') ?? '' }}</p>
                                        </td>
                                        <td class="p-4">
                                            <p class="text-sm text-slate-700">{{ $passenger->booking->flight->flight_number ?? 'N/A' }}</p>
                                            <p class="text-xs text-slate-400">
                                                {{ $passenger->booking->flight->departureAirport->iata_code ?? '??' }} → {{ $passenger->booking->flight->arrivalAirport->iata_code ?? '??' }}
                                            </p>
                                        </td>
                                        <td class="p-4">
                                            <span class="text-sm font-medium text-slate-700">{{ $passenger->seat_number ?? '-' }}</span>
                                        </td>
                                        <td class="p-4">
                                            <span class="px-2 py-1 rounded-lg text-xs font-medium {{ $passenger->check_in_status['class'] }}">
                                                {{ $passenger->check_in_status['label'] }}
                                            </span>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex gap-2">
                                                <a href="{{ route('staff.passenger.show', $passenger) }}" 
                                                   class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-medium hover:bg-blue-100">
                                                    Detail
                                                </a>
                                                @if(!$passenger->has_checked_in)
                                                    <form method="POST" action="{{ route('staff.passenger.checkin', $passenger) }}">
                                                        @csrf
                                                        <button type="submit" class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-medium hover:bg-emerald-100">
                                                            Check-in
                                                        </button>
                                                    </form>
                                                @elseif(!$passenger->has_boarded)
                                                    <form method="POST" action="{{ route('staff.passenger.board', $passenger) }}">
                                                        @csrf
                                                        <button type="submit" class="px-3 py-1.5 bg-green-50 text-green-600 rounded-lg text-xs font-medium hover:bg-green-100">
                                                            Boarding
                                                        </button>
                                                    </form>
                                                @elseif(!$passenger->checked_out_at)
                                                    <form method="POST" action="{{ route('staff.passenger.checkout', $passenger) }}">
                                                        @csrf
                                                        <button type="submit" class="px-3 py-1.5 bg-purple-50 text-purple-600 rounded-lg text-xs font-medium hover:bg-purple-100">
                                                            Check-out
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="px-3 py-1.5 bg-slate-50 text-slate-400 rounded-lg text-xs font-medium">Selesai</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-8 text-center text-slate-400">Belum ada data penumpang</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-6">
                    {{ $passengers->links() }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>