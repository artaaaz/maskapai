<x-admin-layout>
   <x-slot name="header">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <h2 class="text-xl font-bold text-black">Kelola Jadwal Penerbangan</h2>
            <a href="{{ route('admin.flights.create') }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg">+ Tambah Penerbangan</a>
        </div>
    </div>
</x-slot>>

    <div class="py-6">
        <div class="w-full px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">{{ session('success') }}</div>
            @endif

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Flight No</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Maskapai</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Rute</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Kursi</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($flights as $flight)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4"><span class="text-sm font-bold text-slate-900">{{ $flight->flight_number }}</span></td>
                                <td class="px-6 py-4"><span class="text-sm text-slate-600">{{ $flight->airline->name }}</span></td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold text-slate-800">{{ $flight->departureAirport->iata_code }} → {{ $flight->arrivalAirport->iata_code }}</span>
                                    <p class="text-xs text-slate-500">{{ $flight->departureAirport->city }} → {{ $flight->arrivalAirport->city }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs text-slate-800">{{ $flight->departure_time->format('d M Y, H:i') }}</p>
                                    <p class="text-xs text-slate-500">s/d {{ $flight->arrival_time->format('d M Y, H:i') }}</p>
                                </td>
                                <td class="px-6 py-4"><span class="text-sm font-bold text-slate-900">Rp {{ number_format($flight->price, 0, ',', '.') }}</span></td>
                                <td class="px-6 py-4"><span class="text-sm text-slate-600">{{ $flight->available_seats }} seats</span></td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.flights.edit', $flight) }}" class="px-3 py-1 bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold rounded">Edit</a>
                                        <form action="{{ route('admin.flights.destroy', $flight) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="px-6 py-8 text-center text-slate-500 text-sm">Belum ada data penerbangan</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>