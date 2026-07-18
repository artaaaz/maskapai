<x-staff-layout title="Detail Penumpang - drgMaskapai" header="Detail Penumpang">
    <x-slot name="headerRight">
        <a href="{{ route('staff.passengers') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-sm transition">&larr; Kembali</a>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">{{ $passenger->full_name_with_title }}</h2>
                    <p class="text-slate-500">{{ $passenger->passport_number }}</p>
                </div>
                <span class="px-3 py-1.5 rounded-lg text-sm font-medium {{ $passenger->check_in_status['class'] }}">
                    {{ $passenger->check_in_status['label'] }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Data Penumpang</h3>
                    <table class="w-full">
                        <tr><td class="py-2 text-slate-500">Nama</td><td class="py-2 font-medium">{{ $passenger->full_name_with_title }}</td></tr>
                        <tr><td class="py-2 text-slate-500">Gender</td><td class="py-2 font-medium">{{ $passenger->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                        <tr><td class="py-2 text-slate-500">Tanggal Lahir</td><td class="py-2 font-medium">{{ $passenger->birth_date ? $passenger->birth_date->format('d/m/Y') : '-' }}</td></tr>
                        <tr><td class="py-2 text-slate-500">Paspor</td><td class="py-2 font-medium">{{ $passenger->passport_number }}</td></tr>
                        <tr><td class="py-2 text-slate-500">Kursi</td><td class="py-2 font-medium">{{ $passenger->seat_number ?? '-' }}</td></tr>
                        <tr><td class="py-2 text-slate-500">Kelas</td><td class="py-2 font-medium">{{ ucfirst($passenger->travel_class ?? 'economy') }}</td></tr>
                    </table>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Data Penerbangan</h3>
                    <table class="w-full">
                        <tr><td class="py-2 text-slate-500">Booking</td><td class="py-2 font-medium">{{ $passenger->booking->booking_code ?? 'N/A' }}</td></tr>
                        <tr><td class="py-2 text-slate-500">Penerbangan</td><td class="py-2 font-medium">{{ $passenger->booking->flight->flight_number ?? 'N/A' }}</td></tr>
                        <tr><td class="py-2 text-slate-500">Maskapai</td><td class="py-2 font-medium">{{ $passenger->booking->flight->airline->name ?? 'N/A' }}</td></tr>
                        <tr><td class="py-2 text-slate-500">Rute</td><td class="py-2 font-medium">{{ $passenger->booking->flight->departureAirport->city ?? '??' }} → {{ $passenger->booking->flight->arrivalAirport->city ?? '??' }}</td></tr>
                        <tr><td class="py-2 text-slate-500">Berangkat</td><td class="py-2 font-medium">{{ $passenger->booking->flight->departure_time ? $passenger->booking->flight->departure_time->format('d/m/Y H:i') : '-' }}</td></tr>
                        <tr><td class="py-2 text-slate-500">Tiba</td><td class="py-2 font-medium">{{ $passenger->booking->flight->arrival_time ? $passenger->booking->flight->arrival_time->format('d/m/Y H:i') : '-' }}</td></tr>
                    </table>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Status Perjalanan</h3>
                <div class="grid grid-cols-4 gap-4">
                    <div class="p-4 rounded-xl {{ $passenger->has_checked_in ? 'bg-green-50 border border-green-200' : 'bg-slate-50' }}">
                        <p class="text-sm font-semibold {{ $passenger->has_checked_in ? 'text-green-700' : 'text-slate-400' }}">Check-in</p>
                        <p class="text-xs {{ $passenger->checked_in_at ? '' : 'text-slate-400' }}">{{ $passenger->checked_in_at ? $passenger->checked_in_at->format('H:i d/m/Y') : 'Belum' }}</p>
                    </div>
                    <div class="p-4 rounded-xl {{ $passenger->has_boarded ? 'bg-green-50 border border-green-200' : 'bg-slate-50' }}">
                        <p class="text-sm font-semibold {{ $passenger->has_boarded ? 'text-green-700' : 'text-slate-400' }}">Boarding</p>
                        <p class="text-xs {{ $passenger->boarded_at ? '' : 'text-slate-400' }}">{{ $passenger->boarded_at ? $passenger->boarded_at->format('H:i d/m/Y') : 'Belum' }}</p>
                    </div>
                    <div class="p-4 rounded-xl {{ $passenger->checked_out_at ? 'bg-green-50 border border-green-200' : 'bg-slate-50' }}">
                        <p class="text-sm font-semibold {{ $passenger->checked_out_at ? 'text-green-700' : 'text-slate-400' }}">Check-out</p>
                        <p class="text-xs {{ $passenger->checked_out_at ? '' : 'text-slate-400' }}">{{ $passenger->checked_out_at ? $passenger->checked_out_at->format('H:i d/m/Y') : 'Belum' }}</p>
                    </div>
                    <div class="p-4 rounded-xl {{ $passenger->booking->status == 'confirmed' ? 'bg-green-50 border border-green-200' : 'bg-slate-50' }}">
                        <p class="text-sm font-semibold {{ $passenger->booking->status == 'confirmed' ? 'text-green-700' : 'text-slate-400' }}">Status</p>
                        <p class="text-xs">{{ ucfirst($passenger->booking->status ?? 'pending') }}</p>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                @if(!$passenger->has_checked_in)
                    <form method="POST" action="{{ route('staff.passenger.checkin', $passenger) }}">
                        @csrf
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl">Check-in Penumpang</button>
                    </form>
                @endif
                @if($passenger->has_checked_in && !$passenger->has_boarded)
                    <form method="POST" action="{{ route('staff.passenger.board', $passenger) }}">
                        @csrf
                        <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl">Boarding Penumpang</button>
                    </form>
                @endif
                @if($passenger->has_boarded && !$passenger->checked_out_at)
                    <form method="POST" action="{{ route('staff.passenger.checkout', $passenger) }}">
                        @csrf
                        <button type="submit" class="px-6 py-2.5 bg-slate-600 hover:bg-slate-700 text-white font-semibold rounded-xl">Check-out Penumpang</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-staff-layout>