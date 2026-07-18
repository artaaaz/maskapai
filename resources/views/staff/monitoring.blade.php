<x-staff-layout title="Monitoring Penumpang - drgMaskapai" header="Monitoring Penumpang">
    <x-slot name="headerRight">
        <span class="text-sm text-slate-500">{{ $passengers->total() }} penumpang</span>
    </x-slot>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-6">
        <form method="GET" action="{{ route('staff.monitoring') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Status</label>
                <select name="status" class="rounded-xl border-slate-200 text-sm">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Penerbangan</label>
                <select name="flight_id" class="rounded-xl border-slate-200 text-sm">
                    <option value="">Semua Penerbangan</option>
                    @foreach($flights as $f)
                        <option value="{{ $f->id }}" {{ request('flight_id') == $f->id ? 'selected' : '' }}>
                            {{ $f->flight_number }} - {{ $f->airline->name ?? '' }} ({{ $f->departure_time->format('d/m H:i') }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Hari Ini</label>
                <select name="today" class="rounded-xl border-slate-200 text-sm">
                    <option value="">Semua</option>
                    <option value="1" {{ request('today') ? 'selected' : '' }}>Hari Ini</option>
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm">Filter</button>
                <a href="{{ route('staff.monitoring') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-sm ml-2">Reset</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Kode Booking</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Penumpang</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Penerbangan</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Maskapai</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Kursi</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Status</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Berangkat</th>
                        <th class="text-left p-4 text-sm font-semibold text-slate-600">Tiba</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($passengers as $passenger)
                        <tr class="border-b border-slate-50 hover:bg-slate-50">
                            <td class="p-4">
                                <a href="{{ route('staff.booking.show', $passenger->booking) }}" class="text-blue-600 font-medium text-sm hover:underline">
                                    {{ $passenger->booking->booking_code ?? 'N/A' }}
                                </a>
                            </td>
                            <td class="p-4">
                                <p class="font-semibold text-slate-800">{{ $passenger->full_name_with_title }}</p>
                                <p class="text-xs text-slate-400">{{ $passenger->passport_number }}</p>
                            </td>
                            <td class="p-4">
                                <p class="text-sm font-medium text-slate-700">{{ $passenger->booking->flight->flight_number ?? 'N/A' }}</p>
                                <p class="text-xs text-slate-400">{{ $passenger->booking->flight->departureAirport->iata_code ?? '??' }} → {{ $passenger->booking->flight->arrivalAirport->iata_code ?? '??' }}</p>
                            </td>
                            <td class="p-4">
                                <span class="text-sm text-slate-700">{{ $passenger->booking->flight->airline->name ?? 'N/A' }}</span>
                            </td>
                            <td class="p-4">
                                <span class="text-sm font-medium text-slate-700">{{ $passenger->seat_number ?? '-' }}</span>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded-lg text-xs font-medium {{ $passenger->check_in_status['class'] }}">{{ $passenger->check_in_status['label'] }}</span>
                            </td>
                            <td class="p-4">
                                <p class="text-sm text-slate-700">{{ $passenger->booking->flight->departure_time ? $passenger->booking->flight->departure_time->format('H:i') : '-' }}</p>
                                <p class="text-xs text-slate-400">{{ $passenger->booking->flight->departure_time ? $passenger->booking->flight->departure_time->format('d/m') : '' }}</p>
                            </td>
                            <td class="p-4">
                                <p class="text-sm text-slate-700">{{ $passenger->booking->flight->arrival_time ? $passenger->booking->flight->arrival_time->format('H:i') : '-' }}</p>
                                <p class="text-xs text-slate-400">{{ $passenger->booking->flight->arrival_time ? $passenger->booking->flight->arrival_time->format('d/m') : '' }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="p-8 text-center text-slate-400">Tidak ada data penumpang</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $passengers->withQueryString()->links() }}
    </div>
</x-staff-layout>