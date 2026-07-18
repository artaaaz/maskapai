<x-staff-layout title="Data Penumpang - drgMaskapai" header="Data Penumpang">
    <x-slot name="headerRight">
        <span class="text-sm text-slate-500">Total {{ $passengers->total() }} penumpang</span>
    </x-slot>

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
                                <p class="text-xs text-slate-400">{{ $passenger->booking->flight->departureAirport->iata_code ?? '??' }} → {{ $passenger->booking->flight->arrivalAirport->iata_code ?? '??' }}</p>
                            </td>
                            <td class="p-4">
                                <span class="text-sm font-medium text-slate-700">{{ $passenger->seat_number ?? '-' }}</span>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded-lg text-xs font-medium {{ $passenger->check_in_status['class'] }}">{{ $passenger->check_in_status['label'] }}</span>
                            </td>
                            <td class="p-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('staff.passenger.show', $passenger) }}" class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-medium hover:bg-blue-100">Detail</a>
                                    @if(!$passenger->has_checked_in)
                                        <form method="POST" action="{{ route('staff.passenger.checkin', $passenger) }}">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-medium hover:bg-emerald-100">Check-in</button>
                                        </form>
                                    @elseif(!$passenger->has_boarded)
                                        <form method="POST" action="{{ route('staff.passenger.board', $passenger) }}">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-amber-50 text-amber-600 rounded-lg text-xs font-medium hover:bg-amber-100">Boarding</button>
                                        </form>
                                    @elseif(!$passenger->checked_out_at)
                                        <form method="POST" action="{{ route('staff.passenger.checkout', $passenger) }}">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-slate-50 text-slate-600 rounded-lg text-xs font-medium hover:bg-slate-100">Check-out</button>
                                        </form>
                                    @else
                                        <span class="px-3 py-1.5 bg-slate-50 text-slate-400 rounded-lg text-xs font-medium">Selesai</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-8 text-center text-slate-400">Belum ada data penumpang</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $passengers->links() }}
    </div>
</x-staff-layout>