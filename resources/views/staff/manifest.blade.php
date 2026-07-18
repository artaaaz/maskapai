<x-staff-layout title="Manifest - {{ $flight->flight_number }}" header="Passenger Manifest">
    <x-slot name="headerRight">
        <a href="{{ route('staff.dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-sm transition">&larr; Kembali</a>
    </x-slot>

    {{-- Flight Info --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 font-bold text-lg">
                    {{ substr($flight->airline->name ?? 'DG', 0, 2) }}
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800">{{ $flight->airline->name ?? 'N/A' }} - {{ $flight->flight_number }}</h3>
                    <p class="text-slate-500">
                        {{ $flight->departureAirport->city ?? '??' }} ({{ $flight->departureAirport->iata_code ?? '??' }}) 
                        → {{ $flight->arrivalAirport->city ?? '??' }} ({{ $flight->arrivalAirport->iata_code ?? '??' }})
                    </p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm font-semibold text-slate-700">Berangkat: {{ $flight->departure_time->format('d M Y H:i') }}</p>
                <p class="text-sm text-slate-500">Tiba: {{ $flight->arrival_time->format('d M Y H:i') }}</p>
                <p class="text-xs text-slate-400">{{ $flight->airplane->model ?? '' }} • {{ $passengers->count() }} penumpang</p>
            </div>
        </div>
    </div>

    {{-- Manifest Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-4 border-b border-slate-100 bg-slate-50">
            <h4 class="font-bold text-slate-700">Daftar Penumpang</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">#</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Penumpang</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Kursi</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Kode Booking</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Status</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Check In</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Boarding</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Check Out</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($passengers as $index => $passenger)
                        <tr class="border-b border-slate-50 hover:bg-slate-50">
                            <td class="p-4 text-sm text-slate-500">{{ $index + 1 }}</td>
                            <td class="p-4">
                                <p class="font-semibold text-slate-800">{{ $passenger->full_name_with_title }}</p>
                                <p class="text-xs text-slate-400">{{ $passenger->passport_number }}</p>
                            </td>
                            <td class="p-4">
                                <span class="text-sm font-bold text-slate-700">{{ $passenger->seat_number ?? '-' }}</span>
                            </td>
                            <td class="p-4">
                                <span class="text-sm font-medium text-blue-600">{{ $passenger->booking->booking_code ?? 'N/A' }}</span>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded-lg text-xs font-medium {{ $passenger->check_in_status['class'] }}">{{ $passenger->check_in_status['label'] }}</span>
                            </td>
                            <td class="p-4">
                                @if($passenger->checked_in_at)
                                    <p class="text-sm text-slate-700">{{ $passenger->checked_in_at->format('H:i') }}</p>
                                    <p class="text-xs text-slate-400">{{ $passenger->checked_in_at->format('d/m') }}</p>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($passenger->boarded_at)
                                    <p class="text-sm text-slate-700">{{ $passenger->boarded_at->format('H:i') }}</p>
                                    <p class="text-xs text-slate-400">{{ $passenger->boarded_at->format('d/m') }}</p>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($passenger->checked_out_at)
                                    <p class="text-sm text-slate-700">{{ $passenger->checked_out_at->format('H:i') }}</p>
                                    <p class="text-xs text-slate-400">{{ $passenger->checked_out_at->format('d/m') }}</p>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="p-8 text-center text-slate-400">Belum ada penumpang untuk penerbangan ini</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Summary --}}
    <div class="mt-6 grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl p-4 border border-slate-100 text-center">
            <p class="text-2xl font-bold text-slate-800">{{ $passengers->where('status', 'waiting')->count() }}</p>
            <p class="text-xs text-slate-500">Waiting</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-100 text-center">
            <p class="text-2xl font-bold text-blue-600">{{ $passengers->where('status', 'checked_in')->count() }}</p>
            <p class="text-xs text-slate-500">Checked In</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-100 text-center">
            <p class="text-2xl font-bold text-amber-600">{{ $passengers->where('status', 'boarded')->count() }}</p>
            <p class="text-xs text-slate-500">Boarded</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-100 text-center">
            <p class="text-2xl font-bold text-green-600">{{ $passengers->where('status', 'completed')->count() }}</p>
            <p class="text-xs text-slate-500">Completed</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-100 text-center">
            <p class="text-2xl font-bold text-red-600">{{ $passengers->where('status', 'no_show')->count() }}</p>
            <p class="text-xs text-slate-500">No Show</p>
        </div>
    </div>
</x-staff-layout>